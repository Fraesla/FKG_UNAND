<?php

namespace App\Http\Controllers\admin;

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

        // Query pakai paginate
        $suratizin = DB::table('surat_izin')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $suratizin->appends($request->all());

        return view('admin.suratizin.index', ['suratizin' => $suratizin]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('surat_izin');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('jenis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('no_bp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('judul_penelitian', 'like', "%{$search}%")
                  ->orWhere('gmail', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('isi_surat', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $suratizin = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $suratizin->appends($request->all());

        return view('admin.suratizin.index', compact('suratizin'));
    }

    public function add(){
        return view('admin.suratizin.create');
    }

    public function create(Request $request){
        DB::table('surat_izin')->insert([  
            'jenis' => $request->jenis,
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'alamat' => $request->alamat,
            'judul_penilitian' => $request->judul_penilitian,
            'gmail' => $request->gmail,
            'no_hp' => $request->no_hp,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'isi_surat' => $request->isi_surat
        ]);

        return redirect('/admin/suratizin')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $suratizin = DB::table('surat_izin')->where('id',$id)->first();
        
        return view('admin.surat_izin.edit',['surat_izin'=>$surat_izin]);
    }

    public function update(Request $request, $id) {
        DB::table('surat_izin')  
            ->where('id', $id)
            ->update([
            'jenis' => $request->jenis,
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'alamat' => $request->alamat,
            'judul_penilitian' => $request->judul_penilitian,
            'gmail' => $request->gmail,
            'no_hp' => $request->no_hp,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'isi_surat' => $request->isi_surat
        ]);

        return redirect('/admin/suratizin')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('surat_izin')->where('id',$id)->delete();

        return redirect('/admin/suratizin')->with("success","Data Berhasil Dihapus !");
    }
}
