<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class KelasController extends Controller
{
    public function read(){
        // $kelas = DB::table('kelas')->orderBy('id','DESC')->get();
        $kelas = DB::table('kelas')
        ->join('prodi', 'kelas.id_prodi', '=', 'prodi.id')
        ->select('kelas.*', 'prodi.nama as nama_prodi')
        ->orderBy('kelas.id', 'DESC')
        ->get();

        return view('admin.master.kelas.index',['kelas'=>$kelas]);
    }

    public function add(){
        $prodi = DB::table('prodi')->orderBy('id','DESC')->get();
        return view('admin.master.kelas.create',['prodi'=>$prodi]);
    }

    public function create(Request $request){
        DB::table('kelas')->insert([  
            'id_prodi' => $request->id_prodi,
            'nama' => $request->nama]);

        return redirect('/admin/kelas')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $kelas = DB::table('kelas')->where('id',$id)->first();
        $prodi = DB::table('prodi')->orderBy('id','DESC')->get();
        
        return view('admin.master.kelas.edit',['kelas'=>$kelas, 'prodi'=>$prodi]);
    }

    public function update(Request $request, $id) {
        DB::table('kelas')  
            ->where('id', $id)
            ->update([
            'id_prodi' => $request->id_prodi,
            'nama' => $request->nama]);

        return redirect('/admin/kelas')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('kelas')->where('id',$id)->delete();

        return redirect('/admin/kelas')->with("success","Data Berhasil Dihapus !");
    }
}
