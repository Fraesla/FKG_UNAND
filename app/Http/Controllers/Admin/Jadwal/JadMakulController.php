<?php

namespace App\Http\Controllers\admin\jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JadMakulController extends Controller
{
    public function read(){
        // $jadmakul = DB::table('jadwal_makul')->orderBy('id','DESC')->get();
        $jadmakul = DB::table('jadwal_makul')
            ->join('tahun_ajaran', 'jadwal_makul.id_tahun_ajaran', '=', 'tahun_ajaran.id')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'tahun_ajaran.nama as tahun_ajaran',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as makul',
                'ruangan.nama as ruangan'
            )
            ->orderBy('jadwal_makul.id', 'DESC')
            ->get();

        return view('admin.jadwal.makul.index',['jadmakul'=>$jadmakul]);
    }

    public function add(){
        $tahunajar = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('admin.jadwal.makul.create',['tahunajar'=>$tahunajar,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function create(Request $request){
        DB::table('jadwal_makul')->insert([  
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jadmakul = DB::table('jadwal_makul')->where('id',$id)->first();
        $tahun_ajaran = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        
        return view('admin.jadwal.makul.edit',['jadmakul'=>$jadmakul,'tahunajar'=>$tahun_ajaran,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        DB::table('jadwal_makul')  
            ->where('id', $id)
            ->update([
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jadwal_makul')->where('id',$id)->delete();

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Dihapus !");
    }
}
