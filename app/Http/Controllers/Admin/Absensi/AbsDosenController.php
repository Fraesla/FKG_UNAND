<?php

namespace App\Http\Controllers\admin\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class AbsDosenController extends Controller
{
    public function read(Request $request){
        // $absdosen = DB::table('absen_dosen')->orderBy('id','DESC')->get();
        $entries = $request->input('entries', 5);

        $absdosen = DB::table('absen_dosen as ad')
            ->join('dosen as d', 'ad.id_dosen', '=', 'd.id')
            ->join('jadwal_makul as jm', 'ad.id_jadwal_dosen', '=', 'jm.id')
            ->join('makul as m', 'jm.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jm.id_ruangan', '=', 'r.id')
            ->select(
                'ad.id',
                'ad.tgl',
                'ad.jam_masuk',
                'ad.jam_pulang',
                'd.nama as nama_dosen',
                'm.nama as makul',
                'r.nama as ruangan',
            )
            ->orderBy('ad.id', 'DESC')
            ->paginate($entries);

        $absdosen->appends($request->all());

        return view('admin.absensi.dosen.index',['absdosen'=>$absdosen]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('absen_dosen as ad')
            ->join('dosen as d', 'ad.id_dosen', '=', 'd.id')
            ->join('jadwal_makul as jm', 'ad.id_jadwal_dosen', '=', 'jm.id')
            ->join('makul as m', 'jm.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jm.id_ruangan', '=', 'r.id')
            ->select(
                'ad.id',
                'ad.tgl',
                'ad.jam_masuk',
                'ad.jam_pulang',
                'd.nama as nama_dosen',
                'm.nama as makul',
                'r.nama as ruangan',
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ad.id', 'like', "%{$search}%")
                  ->orWhere('ad.tgl', 'like', "%{$search}%")
                  ->orWhere('ad.jam_masuk', 'like', "%{$search}%")
                  ->orWhere('ad.jam_pulang', 'like', "%{$search}%")
                  ->orWhere('d.nama', 'like', "%{$search}%")
                  ->orWhere('m.nama', 'like', "%{$search}%")
                  ->orWhere('r.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $absdosen = $query->orderBy('ad.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $absdosen->appends($request->all());

        return view('admin.absensi.dosen.index', compact('absdosen'));
    }

    public function add(){
        $jadmakul = DB::table('jadwal_makul')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'makul.nama as nama_makul',
                'ruangan.nama as nama_ruangan'
            )
            ->orderBy('jadwal_makul.id', 'DESC')
            ->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.absensi.dosen.create',['dosen'=>$dosen,'jadmakul'=>$jadmakul]);
    }

    public function create(Request $request){
        // $request->validate([
        //     'tgl' => 'required|string|max:100',
        //     'jam_masuk' => 'required|string|max:100',
        //     'jam_selesai' => 'required|string|max:100',
        //     'id_dosen' => 'required|string|max:100',
        //     'id_jadwal_dosen' => 'required|string|max:100',
        //     'status' => 'required|string|max:100',
        // ]);
        DB::table('absen_dosen')->insert([  
            'tgl' => $request->tgl,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'id_dosen' => $request->id_dosen,
            'id_jadwal_dosen' => $request->id_jadwal_dosen,
            'status' => $request->status,
            'keterangan' => '',
            'qr'=>' '
        ]);

        return redirect('/admin/absdosen')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $absdosen = DB::table('absen_dosen')->where('id',$id)->first();
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
        
        return view('admin.absensi.dosen.edit',['absdosen'=>$absdosen,'jadmakul'=>$jadmakul,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        // $request->validate([
        //     'tgl' => 'required|string|max:100',
        //     'jam_masuk' => 'required|string|max:100',
        //     'jam_selesai' => 'required|string|max:100',
        //     'id_dosen' => 'required|string|max:100',
        //     'id_jadwal_dosen' => 'required|string|max:100',
        //     'status' => 'required|string|max:100',
        // ]);
        DB::table('absen_dosen')  
            ->where('id', $id)
            ->update([
            'tgl' => $request->tgl,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'id_dosen' => $request->id_dosen,
            'id_jadwal_dosen' => $request->id_jadwal_dosen,
            'status' => $request->status,
            'keterangan' => '',
            'qr'=>' '
        ]);

        return redirect('/admin/absdosen')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('absen_dosen')->where('id',$id)->delete();

        return redirect('/admin/absdosen')->with("success","Data Berhasil Dihapus !");
    }
}
