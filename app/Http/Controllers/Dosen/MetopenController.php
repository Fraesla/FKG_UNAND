<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MetopenController extends Controller
{
    public function read(Request $request, $id_prodi = null){

        $entries = $request->input('entries', 5);
        $user = Auth::user();
        $idDosen = $user->id_dosen ?? $user->id;

        // Query jadwal_metopen
        $query = DB::table('jadwal_metopen')
            ->join('makul', 'jadwal_metopen.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_metopen.id_ruangan', '=', 'ruangan.id')
            ->join('dosen', 'jadwal_metopen.id_dosen', '=', 'dosen.id')
            ->join('prodi', 'jadwal_metopen.id_prodi', '=', 'prodi.id')
            ->select(
                'jadwal_metopen.id',
                'prodi.nama as prodi',
                'jadwal_metopen.tgl',
                'jadwal_metopen.hari',
                'jadwal_metopen.jam_mulai',
                'jadwal_metopen.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            )
            ->where('jadwal_metopen.id_dosen', $idDosen)
            ->orderBy('jadwal_metopen.id', 'DESC');

        // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('jadwal_metopen.id_prodi', $id_prodi);
        }

        // Filter berdasarkan blok (id_kelas) kalau dipilih
        if ($request->filled('id_kelas')) {
            $query->where('jadwal_metopen.id_kelas', $request->id_kelas);
        }

        // Pagination
        $jadmetopen = $query->paginate($entries);
        $jadmetopen->appends($request->all());

        $username = auth()->user()->username;
        $dosen = DB::table('dosen')->where('nip', $username)->first();

        return view('dosen.metopen.index', [
            'jadmetopen' => $jadmetopen,
            'dosen'    => $dosen,
            'id_prodi'    => $id_prodi
        ]);
    } 

    public function feature(Request $request, $id_prodi = null)
    {
        $user = Auth::user();
        $idDosen = $user->id_dosen ?? $user->id;
        $query = DB::table('jadwal_metopen')
            ->join('makul', 'jadwal_metopen.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_metopen.id_ruangan', '=', 'ruangan.id')
            ->join('dosen', 'jadwal_metopen.id_dosen', '=', 'dosen.id')
            ->join('prodi', 'jadwal_metopen.id_prodi', '=', 'prodi.id')
            ->select(
                'jadwal_metopen.id',
                'prodi.nama as prodi',
                'jadwal_metopen.tgl',
                'jadwal_metopen.hari',
                'jadwal_metopen.jam_mulai',
                'jadwal_metopen.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            )
            ->where('jadwal_metopen.id_dosen', $idDosen);

        // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('jadwal_metopen.id_prodi', $id_prodi);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jadwal_metopen.id', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.tgl', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.hari', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.jam_mulai', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.jam_selesai', 'like', "%{$search}%")
                  ->orWhere('prodi.nama', 'like', "%{$search}%")
                  ->orWhere('makul.nama', 'like', "%{$search}%")
                  ->orWhere('dosen.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan.nama', 'like', "%{$search}%");
            });
        }


        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $jadmetopen = $query->orderBy('jadwal_metopen.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jadmetopen->appends($request->all());

        $username = auth()->user()->username;
        $dosen = DB::table('dosen')->where('nip', $username)->first();

        return view('dosen.metopen.index', compact('jadmetopen','dosen','id_prodi'));
    }

    public function add($id_prodi = null){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('dosen.metopen.create',['blok'=>$blok,'dosen'=>$dosen,'makul'=>$makul,'ruangan'=>$ruangan,'id_prodi'=>$id_prodi]);
    }

    public function create(Request $request){
        $user = Auth::user();
        $id_dosen = $user->id_dosen ?? $user->id;
        // Validasi input
        $request->validate([
            'tgl' => 'required|date',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => 'required|exists:makul,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'keterangan' => 'nullable|string',
        ],[
            'tgl.required' => 'Tanggal wajib diisi.',
            'hari.required' => 'Hari wajib diisi.',
            'jam_mulai.required' => 'Jam Mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format Jam Mulai tidak valid (gunakan format HH:MM).',
            'jam_selesai.required' => 'Jam Selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format Jam Selesai tidak valid (gunakan format HH:MM).',
            'jam_selesai.after' => 'Jam Selesai harus setelah Jam Mulai.',
            'id_makul.exists' => 'Mata Kuliah yang dipilih tidak valid..',
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid..',
        ]);

        $id_jadwal = DB::table('jadwal_metopen')->insertGetId([   
            'id_prodi' => $request->id_prodi,
            'tgl' => $request->tgl,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_dosen' => $id_dosen,
            'id_ruangan' => $request->id_ruangan,
            'keterangan' => $request->keterangan,
        ]);

        // Simpan data absen baru
        DB::table('absen_dosen')->insert([
            'tgl'         => $request->tgl,
            'jam_masuk'   => $request->jam_mulai,
            'jam_pulang'  => $request->jam_selesai,
            'id_dosen'    => $request->id_dosen,
            'id_jadwal_dosen' => 'M'.$id_jadwal,
            'status'      => 'belum absen',
            'keterangan'  => '',
            'qr'          => '',
        ]);

        return redirect('/dosen/metopen/'.$request->id_prodi)->with("success","Data Berhasil Ditambah !");
    }

    public function absen($id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = DB::table('jadwal_metopen')->where('id', $id)->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal tidak ditemukan!');
        }

         // Cek apakah sudah ada absen dengan id_jadwal_dosen yang sama
        $cekDuplikat = DB::table('absen_dosen')
            ->whereRaw("REGEXP_REPLACE(id_jadwal_dosen, '[^0-9]', '') = ?", [$jadwal->id])
            ->exists();

        if ($cekDuplikat) {
            return redirect('/dosen/metopen/'.$jadwal->id_prodi)
                ->with('error', 'Absen untuk jadwal ini sudah ada!');
        }

        // Simpan data absen baru
        DB::table('absen_dosen')->insert([
            'tgl'         => $jadwal->tgl,
            'jam_masuk'   => $jadwal->jam_mulai,
            'jam_pulang'  => $jadwal->jam_selesai,
            'id_dosen'    => $jadwal->id_dosen,
            'id_jadwal_dosen' => 'M'.$jadwal->id,
            'status'      => 'belum absen',
            'keterangan'  => '',
            'qr'          => '',
        ]);

        return redirect('/dosen/absendosen')
            ->with('success', 'Absen dosen dari data blok berhasil ditambahkan!');
    }

    public function materi($id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = DB::table('jadwal_metopen')->where('id', $id)->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal tidak ditemukan!');
        }

        // Cek apakah sudah ada materi dengan id_jadwal_blok yang sama
        $cekDuplikat = DB::table('materi')
            ->where('id_jadwal_metopen', $jadwal->id)
            ->exists();

        if ($cekDuplikat) {
            return redirect('/dosen/metopen/'.$jadwal->id_prodi)
                ->with('error', 'Materi untuk jadwal ini sudah ada!');
        }

        // Simpan data materi baru
        DB::table('materi')->insert([
            'id_jadwal_blok' => '',
            'id_jadwal_metopen' => $jadwal->id,
            'id_absen_dosen' => '',
            'judul' => '',
            'file' => '',
        ]);

        return redirect('/dosen/materi')->with('success', 'Materi dari data blok berhasil ditambahkan!');
    } 

    public function nilai($id)
    {
        // Ambil data jadwal berdasarkan ID
        $jadwal = DB::table('jadwal_metopen')->where('id', $id)->first();

        if (!$jadwal) {
            return redirect()->back()->with('error', 'Data jadwal tidak ditemukan!');
        }

        // // Cek apakah sudah ada absen dengan id_jadwal_dosen yang sama
        $cekDuplikat = DB::table('nilai')
            ->where('id_makul', $jadwal->id_makul)
            ->exists();

        if ($cekDuplikat) {
            return redirect('/admin/jadmetopen/'.$jadwal->id_prodi)
                ->with('error', 'Data Nilai untuk jadwal Mata Kuliah ini sudah ada!');
        }

        // Simpan data absen baru
        DB::table('nilai')->insert([
            'id_makul'         => $jadwal->id_makul,
            'id_dosen'   => $jadwal->id_dosen,
            'id_mahasiswa'  => '',
            'nilai'    => 0,
        ]);

        return redirect('/dosen/nilai')
            ->with('success', 'Data Nilai dari data metopen berhasil ditambahkan!');
    }

    public function edit($id){
        $jadmetopen = DB::table('jadwal_metopen')->where('id',$id)->first();
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        
        return view('dosen.metopen.edit',['jadmetopen'=>$jadmetopen,'blok'=>$blok,'dosen'=>$dosen,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        // Validasi input
        $request->validate([
            'tgl' => 'required|date',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => 'required|exists:makul,id',
            'id_ruangan' => 'required|exists:ruangan,id',
            'keterangan' => 'nullable|string',
        ],[
            'tgl.required' => 'Tanggal wajib diisi.',
            'hari.required' => 'Hari wajib diisi.',
            'jam_mulai.required' => 'Jam Mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format Jam Mulai tidak valid (gunakan format HH:MM).',
            'jam_selesai.required' => 'Jam Selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format Jam Selesai tidak valid (gunakan format HH:MM).',
            'jam_selesai.after' => 'Jam Selesai harus setelah Jam Mulai.',
            'id_makul.exists' => 'Mata Kuliah yang dipilih tidak valid..',
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid..',
        ]);

        DB::table('jadwal_metopen')  
            ->where('id', $id)
            ->update([
            'tgl' => $request->tgl,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan,
            'id_ruangan' => $keterangan
        ]);

        // =========================================
        //  CEK APAKAH ADA RECORD ABSEN UNTUK JADWAL
        // =========================================
        $id_jadwal_dosen = 'M' . $id;

        $cekAbsen = DB::table('absen_dosen')
            ->where('id_jadwal_dosen', $id_jadwal_dosen)
            ->first();

        // =========================================
        //  JIKA ADA → UPDATE
        //  JIKA TIDAK ADA → INSERT BARU
        // =========================================
        if ($cekAbsen) {

            DB::table('absen_dosen')
            ->where('id_jadwal_dosen', $id_jadwal_dosen)
            ->update([
                'tgl'        => $request->tgl,
                'jam_masuk'  => $request->jam_mulai,
                'jam_pulang' => $request->jam_selesai,
                'id_dosen'   => $request->id_dosen,
            ]);

        } else {

            DB::table('absen_dosen')->insert([
                'id_jadwal_dosen' => $id_jadwal_dosen,
                'tgl'             => $request->tgl,
                'jam_masuk'       => $request->jam_mulai,
                'jam_pulang'      => $request->jam_selesai,
                'id_dosen'        => $request->id_dosen,
                'status'          => 'belum absen',
                'keterangan'      => '',
                'qr'              => '',
            ]);

        }

        return redirect('/dosen/metopen/'.$request->id_prodi)->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        // Ambil data jadwal dulu sebelum dihapus
        $jadwal = DB::table('jadwal_metopen')->where('id', $id)->first();

        // Jika data tidak ditemukan
        if (!$jadwal) {
            return redirect()->back()->with("error", "Data tidak ditemukan!");
        }

        // Buat ID absensi dosen
        $id_jadwal_dosen = 'M' . $id;

        // Hapus data absen dosen
        DB::table('absen_dosen')->where('id_jadwal_dosen', $id_jadwal_dosen)->delete();

        // Hapus jadwal
        DB::table('jadwal_metopen')->where('id', $id)->delete();

        return redirect('/dosen/metopen/'.$jadwal->id_prodi)->with("success","Data Berhasil Dihapus !");
    }
}
