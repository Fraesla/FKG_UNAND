<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MakulController extends Controller
{
    public function read(Request $request){

         $entries = $request->input('entries', 5);

        // Query pakai paginate
        $makul = DB::table('makul')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $makul->appends($request->all());

        return view('admin.master.makul.index',['makul'=>$makul]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('makul');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $makul = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $makul->appends($request->all());

        return view('admin.master.makul.index', compact('makul'));
    }

    public function add(){
        return view('admin.master.makul.create');
    }

    public function create(Request $request){
        DB::table('makul')->insert([  
            'kode' => $request->kode,'nama' => $request->nama]);

        return redirect('/admin/makul')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $makul = DB::table('makul')->where('id',$id)->first();
        
        return view('admin.master.makul.edit',['makul'=>$makul]);
    }

    public function update(Request $request, $id) {
        DB::table('makul')  
            ->where('id', $id)
            ->update([
            'kode' => $request->kode,'nama' => $request->nama]);

        return redirect('/admin/makul')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('makul')->where('id',$id)->delete();

        return redirect('/admin/makul')->with("success","Data Berhasil Dihapus !");
    }
}
