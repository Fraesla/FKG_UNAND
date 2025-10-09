<?php

namespace App\Http\Controllers\Admin\Jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JadMetopenController extends Controller
{
    public function read(Request $request){

        $entries = $request->input('entries', 5);

        // Data blok (kelas) untuk select option
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();

        // Query jadwal_metopen
        $query = DB::table('jadwal_metopen')
            ->join('makul', 'jadwal_metopen.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_metopen.id_ruangan', '=', 'ruangan.id')
            ->join('dosen', 'jadwal_metopen.id_dosen', '=', 'dosen.id')
            ->select(
                'jadwal_metopen.id',
                'jadwal_metopen.minggu',
                'jadwal_metopen.hari',
                'jadwal_metopen.jam_mulai',
                'jadwal_metopen.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            )
            ->orderBy('jadwal_metopen.id', 'DESC');

        // Filter berdasarkan blok (id_kelas) kalau dipilih
        if ($request->filled('id_kelas')) {
            $query->where('jadwal_metopen.id_kelas', $request->id_kelas);
        }

        // Pagination
        $jadmetopen = $query->paginate($entries);
        $jadmetopen->appends($request->all());

        return view('admin.jadwal.metopen.index', [
            'jadmetopen' => $jadmetopen,
            'blok'     => $blok
        ]);
    } 

    public function feature(Request $request)
    {
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $query = DB::table('jadwal_metopen')
            ->join('makul', 'jadwal_metopen.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_metopen.id_ruangan', '=', 'ruangan.id')
            ->join('dosen', 'jadwal_metopen.id_dosen', '=', 'dosen.id')
            ->select(
                'jadwal_metopen.id',
                'jadwal_metopen.minggu',
                'jadwal_metopen.hari',
                'jadwal_metopen.jam_mulai',
                'jadwal_metopen.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jadwal_metopen.id', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.hari', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.jam_mulai', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.jam_selesai', 'like', "%{$search}%")
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

        return view('admin.jadwal.metopen.index', compact('jadmetopen','blok'));
    }

    public function add(){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('admin.jadwal.metopen.create',['blok'=>$blok,'dosen'=>$dosen,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function create(Request $request){
        // Validasi input
        $request->validate([
            'minggu' => 'required|integer|min:1|max:6',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => 'required|exists:makul,id',
            'id_dosen' => 'required|exists:dosen,id',
            'id_ruangan' => 'required|exists:ruangan,id',
        ],[
            'minggu.required' => 'Minggu ke- wajib diisi.',
            'minggu.integer' => 'Minggu ke- yang dipilih tidak valid.',
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

        DB::table('jadwal_metopen')->insert([  
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_dosen' => $request->id_dosen,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmetopen')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jadmetopen = DB::table('jadwal_metopen')->where('id',$id)->first();
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        
        return view('admin.jadwal.metopen.edit',['jadmetopen'=>$jadmetopen,'blok'=>$blok,'dosen'=>$dosen,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        // Validasi input
        $request->validate([
            'minggu' => 'required|integer|min:1|max:6',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => 'required|exists:makul,id',
            'id_dosen' => 'required|exists:dosen,id',
            'id_ruangan' => 'required|exists:ruangan,id',
        ],[
            'minggu.required' => 'Minggu ke- wajib diisi.',
            'minggu.integer' => 'Minggu ke- yang dipilih tidak valid.',
            'minggu.min' => 'Minggu ke- yang dipilih tidak valid.',
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

        DB::table('jadwal_metopen')  
            ->where('id', $id)
            ->update([
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_dosen' => $request->id_dosen,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmetopen')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jadwal_metopen')->where('id',$id)->delete();

        return redirect('/admin/jadmetopen')->with("success","Data Berhasil Dihapus !");
    }
}
