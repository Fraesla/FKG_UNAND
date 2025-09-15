<?php

namespace App\Http\Controllers\admin\Jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;


class JadDosenController extends Controller
{
    public function read(){
        // $jaddosen = DB::table('jadwal_dosen')->orderBy('id','DESC')->get();
        $jaddosen = DB::table('jadwal_dosen')
            ->join('jadwal_makul', 'jadwal_dosen.id_jadwal_makul', '=', 'jadwal_makul.id')
            ->join('dosen', 'jadwal_dosen.id_dosen', '=', 'dosen.id')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_dosen.id',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as makul',
                'ruangan.nama as ruangan',
                'dosen.nama as nama_dosen'
            )
            ->orderBy('jadwal_dosen.id', 'DESC')
            ->get();

        return view('admin.jadwal.dosen.index',['jaddosen'=>$jaddosen]);
    }
    public function add(){
        $jadmakul = DB::table('jadwal_makul')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as nama_makul',
                'ruangan.nama as nama_ruangan'
            )
            ->orderBy('jadwal_makul.id', 'DESC')
            ->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.jadwal.dosen.create',['dosen'=>$dosen,'jadmakul'=>$jadmakul]);
    }

    public function create(Request $request){
        DB::table('jadwal_dosen')->insert([  
            'id_jadwal_makul' => $request->id_jadwal_makul,
            'id_dosen' => $request->id_dosen
        ]);

        return redirect('/admin/jaddosen')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jaddosen = DB::table('jadwal_dosen')->where('id',$id)->first();
        $jadmakul = DB::table('jadwal_makul')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as nama_makul',
                'ruangan.nama as nama_ruangan'
            )
            ->orderBy('jadwal_makul.id', 'DESC')
            ->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        
        return view('admin.jadwal.dosen.edit',['jaddosen'=>$jaddosen,'jadmakul'=>$jadmakul,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        DB::table('jadwal_dosen')  
            ->where('id', $id)
            ->update([
            'id_jadwal_makul' => $request->id_jadwal_makul,
            'id_dosen' => $request->id_dosen
        ]);

        return redirect('/admin/jaddosen')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jadwal_dosen')->where('id',$id)->delete();

        return redirect('/admin/jaddosen')->with("success","Data Berhasil Dihapus !");
    }
}
