<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MakulController extends Controller
{
    public function read(){
        $makul = DB::table('makul')->orderBy('id','DESC')->get();

        return view('admin.master.makul.index',['makul'=>$makul]);
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
