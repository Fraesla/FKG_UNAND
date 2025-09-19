<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class PengajuanController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $pengajuan = DB::table('pengajuan')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $pengajuan->appends($request->all());

        return view('admin.pengajuan.index', ['pengajuan' => $pengajuan]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('pengajuan');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('no_bp', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $pengajuan = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $pengajuan->appends($request->all());

        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function add(){
        return view('admin.pengajuan.create');
    }

    public function create(Request $request){
        DB::table('pengajuan')->insert([  
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'no_hp' => $request->no_hp,
            'gmail' => $request->gmail,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'surat_pengajuan' => $request->surat_pengajuan,
            'judul' => $request->judul,
            'krs' => $request->krs
        ]);

        return redirect('/admin/pengajuan')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $pengajuan = DB::table('pengajuan')->where('id',$id)->first();
        
        return view('admin.pengajuan.edit',['pengajuan'=>$pengajuan]);
    }

    public function update(Request $request, $id) {
        DB::table('pengajuan')  
            ->where('id', $id)
            ->update([
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'no_hp' => $request->no_hp,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'surat_pengajuan' => $request->surat_pengajuan,
            'judul' => $request->judul,
            'krs' => $request->krs
        ]);

        return redirect('/admin/pengajuan')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('pengajuan')->where('id',$id)->delete();

        return redirect('/admin/pengajuan')->with("success","Data Berhasil Dihapus !");
    }
}
