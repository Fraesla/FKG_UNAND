<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JurusanController extends Controller
{
    public function read(){
        // $jurusan = DB::table('jurusan')->orderBy('id','DESC')->get();
        $jurusan = DB::table('jurusan')
        ->join('fakultas', 'jurusan.id_fakultas', '=', 'fakultas.id')
        ->select('jurusan.*', 'fakultas.nama as nama_fakultas')
        ->orderBy('jurusan.id', 'DESC')
        ->get();

        return view('admin.master.jurusan.index',['jurusan'=>$jurusan]);
    }

    public function add(){
        $fakultas = DB::table('fakultas')->orderBy('id','DESC')->get();
        return view('admin.master.jurusan.create',['fakultas'=>$fakultas]);
    }

    public function create(Request $request){
        DB::table('jurusan')->insert([  
            'id_fakultas' => $request->id_fakultas,
            'nama' => $request->nama]);

        return redirect('/admin/jurusan')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jurusan = DB::table('jurusan')->where('id',$id)->first();
        $fakultas = DB::table('fakultas')->orderBy('id','DESC')->get();
        
        return view('admin.master.jurusan.edit',['jurusan'=>$jurusan, 'fakultas'=>$fakultas]);
    }

    public function update(Request $request, $id) {
        DB::table('jurusan')  
            ->where('id', $id)
            ->update([
            'id_fakultas' => $request->id_fakultas,
            'nama' => $request->nama]);

        return redirect('/admin/jurusan')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jurusan')->where('id',$id)->delete();

        return redirect('/admin/jurusan')->with("success","Data Berhasil Dihapus !");
    }
}
