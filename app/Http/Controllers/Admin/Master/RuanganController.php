<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class RuanganController extends Controller
{
    public function read(Request $request){
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $ruangan = DB::table('ruangan')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $ruangan->appends($request->all());

        return view('admin.master.ruangan.index', ['ruangan' => $ruangan]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('ruangan');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $ruangan = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $ruangan->appends($request->all());

        return view('admin.master.ruangan.index', compact('ruangan'));
    }

    public function add(){
        return view('admin.master.ruangan.create');
    }

    public function create(Request $request){
        DB::table('ruangan')->insert([  
            'nama' => $request->nama]);

        return redirect('/admin/ruangan')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $ruangan = DB::table('ruangan')->where('id',$id)->first();
        
        return view('admin.master.ruangan.edit',['ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        DB::table('ruangan')  
            ->where('id', $id)
            ->update([
            'nama' => $request->nama]);

        return redirect('/admin/ruangan')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('ruangan')->where('id',$id)->delete();

        return redirect('/admin/ruangan')->with("success","Data Berhasil Dihapus !");
    }
}
