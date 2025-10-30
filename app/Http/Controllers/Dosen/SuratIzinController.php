<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SuratIzinController extends Controller
{
    public function read(Request $request)
    {
         $entries = $request->input('entries', 5);
        $user = Auth::user();

        // Ambil ID dosen login
        $id_dosen = $user->id_dosen ?? $user->id;

        $suratizin = DB::table('surat_izin')
            ->join('mahasiswa', 'surat_izin.id_mahasiswa', '=', 'mahasiswa.id')
            ->leftJoin('dosen as dosen1', 'surat_izin.dosen_pembimbing_1', '=', 'dosen1.id')
            ->leftJoin('dosen as dosen2', 'surat_izin.dosen_pembimbing_2', '=', 'dosen2.id')
            ->select(
                'surat_izin.*',
                'mahasiswa.nama as nama',
                'mahasiswa.nobp',
                'dosen1.nama as dosen_pembimbing_1',
                'dosen2.nama as dosen_pembimbing_2'
            )
            ->where(function ($query) use ($id_dosen) {
                $query->where('surat_izin.dosen_pembimbing_1', $id_dosen)
                      ->orWhere('surat_izin.dosen_pembimbing_2', $id_dosen);
            })
            ->orderBy('surat_izin.id', 'DESC')
            ->paginate($entries);

        $suratizin->appends($request->all());

        return view('dosen.suratizin.index', compact('suratizin'));
    }

    public function feature(Request $request)
    {
        // Ambil data user login
        $user = Auth::user();

        // Ambil ID dosen dari user login
        $id_dosen = $user->id_dosen ?? $user->id;

        // Base query
        $query = DB::table('surat_izin')
            ->join('mahasiswa', 'surat_izin.id_mahasiswa', '=', 'mahasiswa.id')
            ->leftJoin('dosen as dosen1', 'surat_izin.dosen_pembimbing_1', '=', 'dosen1.id')
            ->leftJoin('dosen as dosen2', 'surat_izin.dosen_pembimbing_2', '=', 'dosen2.id')
            ->select(
                'surat_izin.*',
                'mahasiswa.nama as nama',
                'mahasiswa.nobp',
                'dosen1.nama as dosen_pembimbing_1',
                'dosen2.nama as dosen_pembimbing_2'
            )
            ->where(function ($q) use ($id_dosen) {
                $q->where('surat_izin.dosen_pembimbing_1', $id_dosen)
                  ->orWhere('surat_izin.dosen_pembimbing_2', $id_dosen);
            });

        // 🔍 Fitur search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('surat_izin.id', 'like', "%{$search}%")
                  ->orWhere('surat_izin.jenis', 'like', "%{$search}%")
                  ->orWhere('surat_izin.judul_penelitian', 'like', "%{$search}%")
                  ->orWhere('surat_izin.isi_surat', 'like', "%{$search}%")
                  // 🔽 Kolom dari tabel mahasiswa dan dosen
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%")
                  ->orWhere('dosen1.nama', 'like', "%{$search}%")
                  ->orWhere('dosen2.nama', 'like', "%{$search}%");
            });
        }

        // 🔢 Jumlah data per halaman
        $entries = $request->get('entries', 10);

        // 🔹 Ambil data dengan pagination
        $suratizin = $query->orderBy('surat_izin.id', 'DESC')->paginate($entries);

        // Bawa query string ke pagination
        $suratizin->appends($request->all());

        // Tampilkan ke view
        return view('dosen.suratizin.index', compact('suratizin'));
    } 

    public function add(){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('dosen.suratizin.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'jenis' => 'required|string|max:255',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'judul_penelitian' => 'required|string|max:255',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'isi_surat' => 'required|string',
        ],[
            'jenis.required' => 'Jenis wajib diisi.',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'judul_penelitian.required' => 'Judul Penelitian wajib diisi.',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid..',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid..',
            'isi_surat.required' => 'Isi surat wajib diisi.',
        ]);
        DB::table('surat_izin')->insert([  
            'jenis' => $request->jenis,
            'id_mahasiswa' => $request->id_mahasiswa,
            'judul_penelitian' => $request->judul_penelitian,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'isi_surat' => $request->isi_surat
        ]);

        return redirect('/dosen/suratizin')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $suratizin = DB::table('surat_izin')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('dosen.suratizin.edit',['suratizin'=>$suratizin,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        // Validasi input
        $request->validate([
            'jenis' => 'required|string|max:255',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'judul_penelitian' => 'required|string|max:255',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'isi_surat' => 'required|string',
        ],[
            'jenis.required' => 'Jenis wajib diisi.',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'judul_penelitian.required' => 'Judul Penelitian wajib diisi.',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid..',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid..',
            'isi_surat.required' => 'Isi surat wajib diisi.',
        ]);
        DB::table('surat_izin')  
            ->where('id', $id)
            ->update([
            'jenis' => $request->jenis,
            'id_mahasiswa' => $request->id_mahasiswa,
            'judul_penelitian' => $request->judul_penelitian,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'isi_surat' => $request->isi_surat
        ]);

        return redirect('/dosen/suratizin')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('surat_izin')->where('id',$id)->delete();

        return redirect('/dosen/suratizin')->with("success","Data Berhasil Dihapus !");
    }
}
