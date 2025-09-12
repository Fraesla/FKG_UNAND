<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class FakultasController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function read(){
        $fakultas = DB::table('fakultas')->orderBy('id','DESC')->get();

        return view('admin.master.fakultas.index',['fakultas'=>$fakultas]);
    }

    public function add(){
        return view('admin.master.fakultas.create');
    }

    public function create(Request $request){
        DB::table('fakultas')->insert([  
            'nama' => $request->nama]);

        return redirect('/admin/fakultas')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $fakultas = DB::table('fakultas')->where('id',$id)->first();
        
        return view('admin.master.fakultas.edit',['fakultas'=>$fakultas]);
    }

    public function update(Request $request, $id) {
        DB::table('fakultas')  
            ->where('id', $id)
            ->update([
            'nama' => $request->nama]);

        return redirect('/admin/fakultas')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('fakultas')->where('id',$id)->delete();

        return redirect('/admin/fakultas')->with("success","Data Berhasil Dihapus !");
    }
}
