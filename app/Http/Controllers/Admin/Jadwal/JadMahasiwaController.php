<?php

namespace App\Http\Controllers\admin\Jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JadMahasiwaController extends Controller
{
    public function read(Request $request){
        // $jaddosen = DB::table('jadwal_dosen')->orderBy('id','DESC')->get();
        $entries = $request->input('entries', 5);

        $jadmahasiswa = DB::table('jadwal_mahasiswa as jm')
            ->join('jadwal_makul as jmk', 'jm.id_jadwal_makul', '=', 'jmk.id')
            ->join('mahasiswa as mh', 'jm.id_mahasiwa', '=', 'mh.id')
            ->join('makul as m', 'jmk.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jmk.id_ruangan', '=', 'r.id')
            ->join('jadwal_dosen as jd', 'jmk.id', '=', 'jd.id_jadwal_makul')
            ->join('dosen as d', 'jd.id_dosen', '=', 'd.id')
            ->select(
                'jm.id',
                'jmk.hari',
                'jmk.jam_mulai',
                'jmk.jam_selesai',
                'm.nama as makul',
                'r.nama as ruangan',
                'd.nama as dosen',
                'mh.nama as nama_mahasiswa'
            )
            ->orderBy('jm.id', 'DESC')
            ->paginate($entries);

        $jadmahasiswa->appends($request->all());

        return view('admin.jadwal.mahasiswa.index',['jadmahasiswa'=>$jadmahasiswa]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('jadwal_mahasiswa as jm')
            ->join('jadwal_makul as jmk', 'jm.id_jadwal_makul', '=', 'jmk.id')
            ->join('mahasiswa as mh', 'jm.id_mahasiwa', '=', 'mh.id')
            ->join('makul as m', 'jmk.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jmk.id_ruangan', '=', 'r.id')
            ->join('jadwal_dosen as jd', 'jmk.id', '=', 'jd.id_jadwal_makul')
            ->join('dosen as d', 'jd.id_dosen', '=', 'd.id')
            ->select(
                'jm.id',
                'jmk.hari',
                'jmk.jam_mulai',
                'jmk.jam_selesai',
                'm.nama as makul',
                'r.nama as ruangan',
                'd.nama as dosen',
                'mh.nama as nama_mahasiswa'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
               $q->where('jm.id', 'like', "%{$search}%")
                  ->orWhere('jmk.hari', 'like', "%{$search}%")
                  ->orWhere('jmk.jam_mulai', 'like', "%{$search}%")
                  ->orWhere('jmk.jam_selesai', 'like', "%{$search}%")
                  ->orWhere('m.nama', 'like', "%{$search}%")
                  ->orWhere('r.nama', 'like', "%{$search}%")
                  ->orWhere('d.nama', 'like', "%{$search}%")
                  ->orWhere('mh.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $jadmahasiswa = $query->orderBy('jm.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jadmahasiswa->appends($request->all());

        return view('admin.jadwal.mahasiswa.index', compact('jadmahasiswa'));
    }

    public function add(){
         $jadmakul = DB::table('jadwal_mahasiswa as jm')
            ->join('jadwal_makul as jmk', 'jm.id_jadwal_makul', '=', 'jmk.id')
            ->join('makul as m', 'jmk.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jmk.id_ruangan', '=', 'r.id')
            ->join('jadwal_dosen as jd', 'jmk.id', '=', 'jd.id_jadwal_makul')
            ->join('dosen as d', 'jd.id_dosen', '=', 'd.id')
            ->select(
                'jm.id',
                'jmk.hari',
                'jmk.jam_mulai',
                'jmk.jam_selesai',
                'm.nama as makul',
                'r.nama as ruangan',
                'd.nama as dosen'
            )
            ->orderBy('jm.id', 'DESC')
            ->get();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        return view('admin.jadwal.mahasiswa.create',['mahasiswa'=>$mahasiswa,'jadmakul'=>$jadmakul]);
    }

    public function create(Request $request){
        DB::table('jadwal_mahasiswa')->insert([  
            'id_jadwal_makul' => $request->id_jadwal_makul,
            'id_mahasiwa' => $request->id_mahasiwa
        ]);

        return redirect('/admin/jadmahasiswa')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jadmahasiswa = DB::table('jadwal_mahasiswa')->where('id',$id)->first();
        $jadmakul = DB::table('jadwal_mahasiswa as jm')
            ->join('jadwal_makul as jmk', 'jm.id_jadwal_makul', '=', 'jmk.id')
            ->join('makul as m', 'jmk.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jmk.id_ruangan', '=', 'r.id')
            ->join('jadwal_dosen as jd', 'jmk.id', '=', 'jd.id_jadwal_makul')
            ->join('dosen as d', 'jd.id_dosen', '=', 'd.id')
            ->select(
                'jm.id',
                'jmk.hari',
                'jmk.jam_mulai',
                'jmk.jam_selesai',
                'm.nama as makul',
                'r.nama as ruangan',
                'd.nama as dosen'
            )
            ->orderBy('jm.id', 'DESC')
            ->get();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        
        return view('admin.jadwal.mahasiswa.edit',['jadmahasiswa'=>$jadmahasiswa,'jadmakul'=>$jadmakul,'mahasiswa'=>$mahasiswa]);
    }

    public function update(Request $request, $id) {
        DB::table('jadwal_mahasiswa')  
            ->where('id', $id)
            ->update([
            'id_jadwal_makul' => $request->id_jadwal_makul,
            'id_mahasiwa' => $request->id_mahasiwa
        ]);

        return redirect('/admin/jadmahasiswa')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jadwal_mahasiswa')->where('id',$id)->delete();

        return redirect('/admin/jadmahasiswa')->with("success","Data Berhasil Dihapus !");
    }
}
