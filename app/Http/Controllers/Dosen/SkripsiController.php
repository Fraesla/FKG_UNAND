<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SkripsiController extends Controller
{
    public function read(Request $request){

        $entries = $request->input('entries', 5);
        $user = Auth::user();
        $idDosen = $user->id_dosen ?? $user->id;
        // Data blok (kelas) untuk select option
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();

        // Query skripsi
        $query = DB::table('skripsi')
            ->join('makul', 'skripsi.id_makul', '=', 'makul.id')
            ->join('ruangan', 'skripsi.id_ruangan', '=', 'ruangan.id')
            ->join('dosen', 'skripsi.id_dosen', '=', 'dosen.id')
            ->select(
                'skripsi.id',
                'skripsi.minggu',
                'skripsi.hari',
                'skripsi.jam_mulai',
                'skripsi.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            )
            ->where('skripsi.id_dosen', $idDosen)
            ->orderBy('skripsi.id', 'DESC');

        // Filter berdasarkan blok (id_kelas) kalau dipilih
        if ($request->filled('id_kelas')) {
            $query->where('skripsi.id_kelas', $request->id_kelas);
        }

        // Pagination
        $skripsi = $query->paginate($entries);
        $skripsi->appends($request->all());

        return view('dosen.skripsi.index', [
            'skripsi' => $skripsi,
            'blok'     => $blok
        ]);
    } 

    public function feature(Request $request)
    {
        $user = Auth::user();
        $idDosen = $user->id_dosen ?? $user->id;
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $query = DB::table('skripsi')
            ->join('makul', 'skripsi.id_makul', '=', 'makul.id')
            ->join('ruangan', 'skripsi.id_ruangan', '=', 'ruangan.id')
            ->join('dosen', 'skripsi.id_dosen', '=', 'dosen.id')
            ->select(
                'skripsi.id',
                'skripsi.minggu',
                'skripsi.hari',
                'skripsi.jam_mulai',
                'skripsi.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            )
            ->where('skripsi.id_dosen', $idDosen);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('skripsi.id', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('skripsi.hari', 'like', "%{$search}%")
                  ->orWhere('skripsi.jam_mulai', 'like', "%{$search}%")
                  ->orWhere('skripsi.jam_selesai', 'like', "%{$search}%")
                  ->orWhere('makul.nama', 'like', "%{$search}%")
                  ->orWhere('dosen.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $skripsi = $query->orderBy('skripsi.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $skripsi->appends($request->all());

        return view('dosen.skripsi.index', compact('skripsi','blok'));
    }

    public function add(){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('dosen.skripsi.create',['blok'=>$blok,'dosen'=>$dosen,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function create(Request $request){

        $user = Auth::user();
        $id_dosen = $user->id_dosen ?? $user->id;
        // Validasi input
        $request->validate([
            'minggu' => 'required|integer|min:1|max:6',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => 'required|exists:makul,id',
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
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid..',
        ]);
        DB::table('skripsi')->insert([  
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_dosen' => $id_dosen,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/dosen/skripsi')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $skripsi = DB::table('skripsi')->where('id',$id)->first();
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        
        return view('dosen.skripsi.edit',['skripsi'=>$skripsi,'blok'=>$blok,'dosen'=>$dosen,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        // Validasi input
        $request->validate([
            'minggu' => 'required|integer|min:1|max:6',
            'hari' => 'required|string|max:255',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'id_makul' => 'required|exists:makul,id',
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
            'id_ruangan.exists' => 'Ruangan yang dipilih tidak valid..',
        ]);
        DB::table('skripsi')  
            ->where('id', $id)
            ->update([
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/dosen/skripsi')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('skripsi')->where('id',$id)->delete();

        return redirect('/dosen/skripsi')->with("success","Data Berhasil Dihapus !");
    }
}
