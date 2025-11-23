<?php

namespace App\Http\Controllers\admin\jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Auth;

class JadMakulController extends Controller
{
    public function read(Request $request){

        $entries = $request->input('entries', 5);

        // Data blok (kelas) untuk select option
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();

        // Query jadwal_makul
        $query = DB::table('jadwal_makul')
            ->join('kelas', 'jadwal_makul.id_kelas', '=', 'kelas.id')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('dosen', 'jadwal_makul.id_dosen', '=', 'dosen.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'kelas.nama as kelas',
                'jadwal_makul.minggu',
                'jadwal_makul.tgl',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            )
            ->orderBy('jadwal_makul.id', 'DESC');

        // Filter berdasarkan blok (id_kelas) kalau dipilih
        if ($request->filled('id_kelas')) {
            $query->where('jadwal_makul.id_kelas', $request->id_kelas);
        }

        // Pagination
        $jadmakul = $query->paginate($entries);
        $jadmakul->appends($request->all());

        return view('admin.jadwal.makul.index', [
            'jadmakul' => $jadmakul,
            'blok'     => $blok
        ]);
    } 

    public function feature(Request $request)
    {
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $query = DB::table('jadwal_makul')
            ->join('kelas', 'jadwal_makul.id_kelas', '=', 'kelas.id')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('dosen', 'jadwal_makul.id_dosen', '=', 'dosen.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'kelas.nama as kelas',
                'jadwal_makul.minggu',
                'jadwal_makul.tgl',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'dosen.nama as dosen',
                'makul.nama as makul',
                'ruangan.nama as ruangan'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jadwal_makul.id', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.tgl', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.hari', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.jam_mulai', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.jam_selesai', 'like', "%{$search}%")
                  ->orWhere('dosen.nama', 'like', "%{$search}%")
                  ->orWhere('makul.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $jadmakul = $query->orderBy('jadwal_makul.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jadmakul->appends($request->all());

        return view('admin.jadwal.makul.index', compact('jadmakul','blok'));
    }

    public function add(){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('admin.jadwal.makul.create',['blok'=>$blok,'makul'=>$makul,'dosen'=>$dosen,'ruangan'=>$ruangan]);
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'minggu' => 'required|integer|min:1|max:6',
            'tgl' => 'required|date',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => [
                'required',
                'exists:makul,id',
                // Rule::unique('jadwal_makul', 'id_makul'),
            ],
            'id_dosen' => 'required|exists:dosen,id',
            'id_ruangan' => 'required|exists:ruangan,id',
        ],[
            'id_kelas.exists' => 'Blok yang dipilih tidak valid...',
            'minggu.required' => 'Minggu ke- wajib diisi.',
            'minggu.integer' => 'Minggu ke- yang dipilih tidak valid.',
            'tgl.required' => 'Tanggal wajib diisi.',
            'hari.required' => 'Hari wajib diisi.',
            'jam_mulai.required' => 'Jam Mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format Jam Mulai tidak valid (gunakan format HH:MM).',
            'jam_selesai.required' => 'Jam Selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format Jam Selesai tidak valid (gunakan format HH:MM).',
            'jam_selesai.after' => 'Jam Selesai harus setelah Jam Mulai.',
            'id_makul.exists' => 'Mata Kuliah yang dipilih tidak valid..',
            // 'id_makul.unique' => 'Mata Kuliah ini sudah terdaftar di jadwal Blok.',
            'id_dosen.exists' => 'Dosen yang dipilih tidak valid..',
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid..',
        ]);

        // Simpan jadwal & ambil ID-nya
        $id_jadwal = DB::table('jadwal_makul')->insertGetId([
            'id_kelas'    => $request->id_kelas,
            'minggu'      => $request->minggu,
            'tgl'         => $request->tgl,
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul'    => $request->id_makul,
            'id_dosen'    => $request->id_dosen,
            'id_ruangan'  => $request->id_ruangan
        ]);

        // Simpan data absen_dosen
        DB::table('absen_dosen')->insert([
            'tgl'             => $request->tgl,
            'jam_masuk'       => $request->jam_mulai,
            'jam_pulang'      => $request->jam_selesai,
            'id_dosen'        => $request->id_dosen,
            'id_jadwal_dosen' => 'B' . $id_jadwal,   // ← otomatis memakai ID dari jadwal_makul
            'status'          => 'belum absen',
            'keterangan'      => '',
            'qr'              => '',
        ]);

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Ditambah !");
    }

    public function absen($id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = DB::table('jadwal_makul')->where('id', $id)->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal tidak ditemukan!');
        }

        // Cek apakah sudah ada absen dengan id_jadwal_dosen yang sama
        $cekDuplikat = DB::table('absen_dosen')
            ->whereRaw("REGEXP_REPLACE(id_jadwal_dosen, '[^0-9]', '') = ?", [$jadwal->id])
            ->exists();

        if ($cekDuplikat) {
            return redirect('/admin/jadmakul')
                ->with('error', 'Absen untuk jadwal ini sudah ada!');
        } 

        // Simpan data absen baru
        DB::table('absen_dosen')->insert([
            'tgl'         => $jadwal->tgl,
            'jam_masuk'   => $jadwal->jam_mulai,
            'jam_pulang'  => $jadwal->jam_selesai,
            'id_dosen'    => $jadwal->id_dosen,
            'id_jadwal_dosen' => 'B'.$jadwal->id,
            'status'      => 'belum absen',
            'keterangan'  => '',
            'qr'          => '',
        ]);

        return redirect('/admin/absdosen')
            ->with('success', 'Absen dosen dari data blok berhasil ditambahkan!');
    }

    public function materi($id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = DB::table('jadwal_makul')->where('id', $id)->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal tidak ditemukan!');
        }

        // Cek apakah sudah ada materi dengan id_jadwal_blok yang sama
        $cekDuplikat = DB::table('materi')
            ->where('id_jadwal_blok', $jadwal->id)
            ->exists();

        if ($cekDuplikat) {
            return redirect('/admin/jadmakul')
                ->with('error', 'Materi untuk jadwal ini sudah ada!');
        }

        // Simpan data materi baru
        DB::table('materi')->insert([
            'id_jadwal_blok' => $jadwal->id,
            'id_jadwal_metopen' => '',
            'id_absen_dosen' => '',
            'judul' => '',
            'file' => '',
        ]);

        return redirect('/admin/materi')->with('success', 'Materi dari data blok berhasil ditambahkan!');
    }
     
    public function nilai($id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = DB::table('jadwal_makul')->where('id', $id)->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal tidak ditemukan!');
        }

        // // Cek apakah sudah ada absen dengan id_makul yang sama
        $cekDuplikat = DB::table('nilai')
            ->where('id_makul', $jadwal->id_makul)
            ->exists();

        if ($cekDuplikat) {
            return redirect('/admin/jadmakul')
                ->with('error', 'Data Nilai untuk jadwal Mata Kuliah ini sudah ada!');
        }

        // Simpan data absen baru
        DB::table('nilai')->insert([
            'id_makul'         => $jadwal->id_makul,
            'id_dosen'   => $jadwal->id_dosen,
            'id_mahasiswa'  => '',
            'nilai'    => 0,
        ]);

        return redirect('/admin/nilai')
            ->with('success', 'Data Nilai dari data blok berhasil ditambahkan!');
    }

    public function edit($id){
        $jadmakul = DB::table('jadwal_makul')->where('id',$id)->first();
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        
        return view('admin.jadwal.makul.edit',['jadmakul'=>$jadmakul,'blok'=>$blok,'dosen'=>$dosen,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
         // Validasi input
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'minggu' => 'required|integer|min:1|max:6',
            'tgl' => 'required|date',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => 'required|exists:makul,id',
            'id_dosen' => 'required|exists:dosen,id',
            'id_ruangan' => 'required|exists:ruangan,id',
        ],[
            'id_kelas.required' => 'Form Blok Silahkan dipilh Dulu...',
            'id_kelas.exists' => 'Blok yang dipilih tidak valid...',
            'minggu.required' => 'Minggu ke- wajib diisi.',
            'minggu.integer' => 'Minggu ke- yang dipilih tidak valid.',
            'tgl.required' => 'Tanggal wajib diisi.',
            'hari.required' => 'Hari wajib diisi.',
            'jam_mulai.required' => 'Jam Mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format Jam Mulai tidak valid (gunakan format HH:MM).',
            'jam_selesai.required' => 'Jam Selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format Jam Selesai tidak valid (gunakan format HH:MM).',
            'jam_selesai.after' => 'Jam Selesai harus setelah Jam Mulai.',
            'id_makul.exists' => 'Mata Kuliah yang dipilih tidak valid..',
            'id_dosen.exists' => 'Dosen yang dipilih tidak valid..',
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid..',
        ]);
        DB::table('jadwal_makul')  
            ->where('id', $id)
            ->update([
            'id_kelas' => $request->id_kelas,
            'minggu' => $request->minggu,
            'tgl' => $request->tgl,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_dosen' => $request->id_dosen,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jadwal_makul')->where('id',$id)->delete();

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Dihapus !");
    }
}
