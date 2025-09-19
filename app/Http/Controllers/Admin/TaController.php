<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TaController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $ta = DB::table('ta')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $ta->appends($request->all());

        return view('admin.ta.index', ['ta' => $ta]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('ta');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('no_bp', 'like', "%{$search}%")
                  ->orWhere('nama_mahasiswa', 'like', "%{$search}%")
                  ->orWhere('dosen_pembimbing', 'like', "%{$search}%")
                  ->orWhere('tgl_pembimbing', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $ta = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $ta->appends($request->all());

        return view('admin.ta.index', compact('ta'));
    }

    public function add(){
        return view('admin.ta.create');
    }

    public function create(Request $request){
        DB::table('ta')->insert([  
            'no_bp' => $request->no_bp,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'dosen_pembimbing' => $request->dosen_pembimbing,
            'tgl_pembimbing' => $request->tgl_pembimbing,
            'catatan' => $request->catatan
        ]);

        return redirect('/admin/ta')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $ta = DB::table('ta')->where('id',$id)->first();
        
        return view('admin.ta.edit',['ta'=>$ta]);
    }

    public function update(Request $request, $id) {
        DB::table('ta')  
            ->where('id', $id)
            ->update([
            'no_bp' => $request->no_bp,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'dosen_pembimbing' => $request->dosen_pembimbing,
            'tgl_pembimbing' => $request->tgl_pembimbing,
            'catatan' => $request->catatan
        ]);

        return redirect('/admin/ta')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('ta')->where('id',$id)->delete();

        return redirect('/admin/ta')->with("success","Data Berhasil Dihapus !");
    }
}
