<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JadwalController extends Controller
{
    public function read(Request $request){
        // $absmahasiswa = DB::table('absen_mahasiswa')->orderBy('id','DESC')->get();
        $entries = $request->input('entries', 5);

        $jadwal = DB::table('absen_mahasiswa as ad')
            ->join('mahasiswa as d', 'ad.id_mahasiswa', '=', 'd.id')
            ->join('jadwal_makul as jm', 'ad.id_jadwal_mahasiswa', '=', 'jm.id')
            ->join('makul as m', 'jm.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jm.id_ruangan', '=', 'r.id')
            ->select(
                'ad.id',
                'ad.tgl',
                'ad.jam_masuk',
                'ad.jam_pulang',
                'ad.status',
                'd.nama as nama_mahasiswa',
                'm.nama as makul',
                'r.nama as ruangan',
                'ad.qr'
            )
            ->orderBy('ad.id', 'DESC')
            ->paginate($entries);

         $jadwal->appends($request->all());

        return view('mahasiswa.jadwal.index',['jadwal'=>$jadwal]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('absen_mahasiswa as ad')
            ->join('mahasiswa as d', 'ad.id_mahasiswa', '=', 'd.id')
            ->join('jadwal_makul as jm', 'ad.id_jadwal_mahasiswa', '=', 'jm.id')
            ->join('makul as m', 'jm.id_makul', '=', 'm.id')
            ->join('ruangan as r', 'jm.id_ruangan', '=', 'r.id')
            ->select(
                'ad.id',
                'ad.tgl',
                'ad.jam_masuk',
                'ad.jam_pulang',
                'ad.status',
                'd.nama as nama_mahasiswa',
                'm.nama as makul',
                'r.nama as ruangan',
                'ad.qr'
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
        $jadwal = $query->orderBy('ad.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jadwal->appends($request->all());

        return view('mahasiswa.jadwal.index', compact('jadwal'));
    }

    // public function add(){
    //     $jadmakul = DB::table('jadwal_makul')
    //         ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
    //         ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
    //         ->select(
    //             'jadwal_makul.id',
    //             'makul.nama as nama_makul',
    //             'ruangan.nama as nama_ruangan'
    //         )
    //         ->orderBy('jadwal_makul.id', 'DESC')
    //         ->get();
    //     $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
    //     return view('admin.absensi.mahasiswa.create',['mahasiswa'=>$mahasiswa,'jadmakul'=>$jadmakul]);
    // }

    // public function create(Request $request){
    //     DB::table('absen_mahasiswa')->insert([  
    //         'tgl' => $request->tgl,
    //         'jam_masuk' => $request->jam_masuk,
    //         'jam_pulang' => $request->jam_pulang,
    //         'id_mahasiswa' => $request->id_mahasiswa,
    //         'id_jadwal_mahasiswa' => $request->id_jadwal_mahasiswa,
    //         'status' => $request->status,
    //         'keterangan' => '',
    //         'qr'=>uniqid()
    //     ]);

    //     return redirect('/admin/absmahasiswa')->with("success","Data Berhasil Ditambah !");
    // }

    public function scan($id) {
        $jadwal = DB::table('absen_mahasiswa')->where('id',$id)->first();
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

        return view('mahasiswa.jadwal.scan', [
            'jadwal' => $jadwal,
            'jadmakul' => $jadmakul,
            'mahasiswa' => auth()->user()
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|integer',
            'mahasiswa_id' => 'required|integer',
        ]);

        // update atau buat data absensi
        $absensi = Absensi::updateOrCreate(
            [
                'jadwal_id'    => $request->jadwal_id,
                'mahasiswa_id' => $request->mahasiswa_id,
            ],
            [
                'status' => 'Hadir',
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil disimpan',
            'data' => $absensi
        ]);
    }

}
