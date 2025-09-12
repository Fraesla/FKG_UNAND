<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ProdiController extends Controller
{
    public function read(){
        // $prodi = DB::table('prodi')->orderBy('id','DESC')->get();
        $prodi = DB::table('prodi')
        ->join('jurusan', 'prodi.id_jurusan', '=', 'jurusan.id')
        ->select('prodi.*', 'jurusan.nama as nama_jurusan')
        ->orderBy('prodi.id', 'DESC')
        ->get();

        return view('admin.master.prodi.index',['prodi'=>$prodi]);
    }

    public function add(){
        $jurusan = DB::table('jurusan')->orderBy('id','DESC')->get();
        return view('admin.master.prodi.create',['jurusan'=>$jurusan]);
    }

    public function create(Request $request){
        DB::table('prodi')->insert([  
            'id_jurusan' => $request->id_jurusan,
            'nama' => $request->nama]);

        return redirect('/admin/prodi')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $prodi = DB::table('prodi')->where('id',$id)->first();
        $jurusan = DB::table('jurusan')->orderBy('id','DESC')->get();
        
        return view('admin.master.prodi.edit',['prodi'=>$prodi, 'jurusan'=>$jurusan]);
    }

    public function update(Request $request, $id) {
        DB::table('prodi')  
            ->where('id', $id)
            ->update([
            'id_jurusan' => $request->id_jurusan,
            'nama' => $request->nama]);

        return redirect('/admin/prodi')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('prodi')->where('id',$id)->delete();

        return redirect('/admin/prodi')->with("success","Data Berhasil Dihapus !");
    }
}
