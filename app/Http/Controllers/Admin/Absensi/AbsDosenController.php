<?php

namespace App\Http\Controllers\admin\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class AbsDosenController extends Controller
{
    public function read(Request $request)
    {
        $entries = $request->input('entries', 5);

        $absdosen = DB::table('absen_dosen as ad')
            ->join('dosen as d', 'ad.id_dosen', '=', 'd.id')
            ->leftJoin('jadwal_makul as jm', DB::raw("CAST(REPLACE(ad.id_jadwal_dosen, 'B', '') AS UNSIGNED)"), '=', 'jm.id')
            ->leftJoin('jadwal_metopen as jp', DB::raw("CAST(REPLACE(ad.id_jadwal_dosen, 'M', '') AS UNSIGNED)"), '=', 'jp.id')
            ->leftJoin('makul as m', function ($join) { 
                $join->on('jm.id_makul', '=', 'm.id') 
                    ->orOn('jp.id_makul', '=', 'm.id'); 
            })
            ->leftJoin('ruangan as r', function ($join) {
                $join->on('jm.id_ruangan', '=', 'r.id')
                     ->orOn('jp.id_ruangan', '=', 'r.id');
            })
            ->select(
                'ad.id',
                'ad.tgl',
                'ad.jam_masuk',
                'ad.jam_pulang',
                'd.nip as nip_dosen',
                'd.nama as nama_dosen',
                DB::raw("COALESCE(m.nama, 'Tidak diketahui') as makul"),
                DB::raw("COALESCE(r.nama, '-') as ruangan"),
                'ad.status as status'
            )
            ->orderBy('ad.id', 'DESC')
            ->paginate($entries);

        $absdosen->appends($request->all());

        return view('admin.absensi.dosen.index', ['absdosen' => $absdosen]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('absen_dosen as ad')
            ->join('dosen as d', 'ad.id_dosen', '=', 'd.id')
            ->leftJoin('jadwal_makul as jm', DB::raw("CAST(REPLACE(ad.id_jadwal_dosen, 'B', '') AS UNSIGNED)"), '=', 'jm.id')
            ->leftJoin('jadwal_metopen as jp', DB::raw("CAST(REPLACE(ad.id_jadwal_dosen, 'M', '') AS UNSIGNED)"), '=', 'jp.id')
            ->leftJoin('makul as m', function ($join) {
                $join->on('jm.id_makul', '=', 'm.id')
                     ->orOn('jp.id_makul', '=', 'm.id');
            })
            ->leftJoin('ruangan as r', function ($join) {
                $join->on('jm.id_ruangan', '=', 'r.id')
                     ->orOn('jp.id_ruangan', '=', 'r.id');
            })
            ->select(
                'ad.id',
                'ad.tgl',
                'ad.jam_masuk',
                'ad.jam_pulang',
                'd.nip as nip_dosen',
                'd.nama as nama_dosen',
                DB::raw("COALESCE(m.nama, '-') as makul"),
                DB::raw("COALESCE(r.nama, '-') as ruangan"),
                'ad.status as status'
            );

        // 🔍 Fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ad.id', 'like', "%{$search}%")
                  ->orWhere('ad.tgl', 'like', "%{$search}%")
                  ->orWhere('ad.jam_masuk', 'like', "%{$search}%")
                  ->orWhere('ad.jam_pulang', 'like', "%{$search}%")
                  ->orWhere('d.nip', 'like', "%{$search}%")
                  ->orWhere('d.nama', 'like', "%{$search}%")
                  ->orWhere('m.nama', 'like', "%{$search}%")
                  ->orWhere('r.nama', 'like', "%{$search}%");
            });
        }

        // 🔢 Jumlah entri per halaman
        $entries = $request->get('entries', 10);

        // 🔁 Pagination
        $absdosen = $query->orderBy('ad.id', 'DESC')->paginate($entries);
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
        $absdosen = DB::table('absen_dosen as am')
            ->join('dosen as d', 'am.id_dosen', '=', 'd.id')

            ->leftJoin('jadwal_makul', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_dosen, 2)"), '=', 'jadwal_makul.id')
                    ->whereRaw("LEFT(am.id_jadwal_dosen, 1) = 'B'");
            })
            ->leftJoin('makul as makul_blok', 'jadwal_makul.id_makul', '=', 'makul_blok.id')
            ->leftJoin('ruangan as ruangan_blok', 'jadwal_makul.id_ruangan', '=', 'ruangan_blok.id')

            ->leftJoin('jadwal_metopen', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_dosen, 2)"), '=', 'jadwal_metopen.id')
                    ->whereRaw("LEFT(am.id_jadwal_dosen, 1) = 'M'");
            })
            ->leftJoin('makul as makul_metopen', 'jadwal_metopen.id_makul', '=', 'makul_metopen.id')
            ->leftJoin('ruangan as ruangan_metopen', 'jadwal_metopen.id_ruangan', '=', 'ruangan_metopen.id')

            ->leftJoin('materi as materi_blok', function ($join) {
                $join->on('materi_blok.id_jadwal_blok', '=', DB::raw("SUBSTRING(am.id_jadwal_dosen, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_dosen, 1) = 'B'");
            })
            ->leftJoin('materi as materi_metopen', function ($join) {
                $join->on('materi_metopen.id_jadwal_metopen', '=', DB::raw("SUBSTRING(am.id_jadwal_dosen, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_dosen, 1) = 'M'");
            })

            ->select(
                'am.*',
                'd.nip as nip_dosen',
                'd.nama as nama_dosen',
                DB::raw('COALESCE(makul_blok.kode, makul_metopen.kode) as kode_makul'),
                DB::raw('COALESCE(makul_blok.nama, makul_metopen.nama) as nama_makul'),
                DB::raw('COALESCE(ruangan_blok.nama, ruangan_metopen.nama) as ruangan'),
                DB::raw('COALESCE(jadwal_makul.hari, jadwal_metopen.hari) as hari'),
                DB::raw('COALESCE(materi_blok.judul, materi_metopen.judul) as judul_materi'),
                DB::raw('COALESCE(materi_blok.file, materi_metopen.file) as file_materi')
            )
            ->where('am.id', $id)  // <--- tambahkan ini
            ->first();             // <--- dan ini
        
        return view('admin.absensi.dosen.edit',['absdosen'=>$absdosen]);
    }

    public function update(Request $request, $id) {
         // Jika status = hadir, maka keterangan otomatis "Hadir"
        if ($request->status === 'hadir') {
            $keterangan = 'Hadir';
        } elseif (in_array($request->status, ['izin', 'sakit', 'belum absen'])) {
            $keterangan = trim($request->keterangan) ?: '-';
        } else {
            $keterangan = 'Alfa';
        }

        $request->validate([
            'status' => 'required|exists:status',
        ],[
            'status.required' => 'Status yang dipilih tidak valid..',
            'status.exists' => 'Status yang dipilih tidak valid..',
        ]);
        DB::table('absen_dosen')  
            ->where('id', $id)
            ->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/admin/absdosen')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('absen_dosen')->where('id',$id)->delete();

        return redirect('/admin/absdosen')->with("success","Data Berhasil Dihapus !");
    }
}
