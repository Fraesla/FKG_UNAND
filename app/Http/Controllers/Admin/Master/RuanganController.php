<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class RuanganController extends Controller
{
    public function read(){
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();

        return view('admin.master.ruangan.index',['ruangan'=>$ruangan]);
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
