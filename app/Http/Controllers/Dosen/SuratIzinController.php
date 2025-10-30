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
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query dengan join ke tabel mahasiswa
        $suratizin = DB::table('surat_izin')
            ->join('mahasiswa', 'surat_izin.id_mahasiswa', '=', 'mahasiswa.id')
            ->select(
                'surat_izin.*',
                'mahasiswa.nama',
                'mahasiswa.nobp'
            )
            ->orderBy('surat_izin.id', 'DESC')
            ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $suratizin->appends($request->all());

        return view('dosen.suratizin.index', compact('suratizin'));
    }

    public function feature(Request $request)
    {
        $query = DB::table('surat_izin')
        ->join('mahasiswa', 'surat_izin.id_mahasiswa', '=', 'mahasiswa.id')
        ->select(
            'surat_izin.*',
            'mahasiswa.nama',
            'mahasiswa.nobp'
        );

        // 🔍 Fitur search
        if ($request->filled('search')) 
        {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('surat_izin.id', 'like', "%{$search}%")
                  ->orWhere('surat_izin.jenis', 'like', "%{$search}%")
                  ->orWhere('surat_izin.judul_penelitian', 'like', "%{$search}%")
                  ->orWhere('surat_izin.dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('surat_izin.dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('surat_izin.isi_surat', 'like', "%{$search}%")
                  // 🔽 Field dari tabel mahasiswa
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%");
            });
        }

    // Show entries (default 10)
    $entries = $request->get('entries', 10);

    // Ambil data dengan pagination
    $suratizin = $query->orderBy('surat_izin.id', 'DESC')->paginate($entries);

    // Supaya pagination tetap bawa query string
    $suratizin->appends($request->all());

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
