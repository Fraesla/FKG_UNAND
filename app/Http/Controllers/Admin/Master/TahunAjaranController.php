<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TahunAjaranController extends Controller
{
    public function read(){
        $tahunajar = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();

        return view('admin.master.tahunajar.index',['tahunajar'=>$tahunajar]);
    }

    public function add(){
        return view('admin.master.tahunajar.create');
    }

    public function create(Request $request){
        DB::table('tahun_ajaran')->insert([  
            'nama' => $request->nama,'status' => $request->status]);

        return redirect('/admin/tahunajar')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $tahunajar = DB::table('tahun_ajaran')->where('id',$id)->first();
        
        return view('admin.master.tahunajar.edit',['tahunajar'=>$tahunajar]);
    }

    public function update(Request $request, $id) {
        DB::table('tahun_ajaran')  
            ->where('id', $id)
            ->update([
            'nama' => $request->nama,'status' => $request->status]);

        return redirect('/admin/tahunajar')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('tahun_ajaran')->where('id',$id)->delete();

        return redirect('/admin/tahunajar')->with("success","Data Berhasil Dihapus !");
    }
}
