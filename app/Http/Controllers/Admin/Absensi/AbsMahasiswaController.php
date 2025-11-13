<?php

namespace App\Http\Controllers\admin\Absensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class AbsMahasiswaController extends Controller
{
    public function read(Request $request){
        
        $entries = $request->input('entries', 5);
        $user = Auth::user();

        $absmahasiswa = DB::table('absen_mahasiswa as am')
            ->join('mahasiswa as m', 'am.id_mahasiswa', '=', 'm.id')
            ->leftJoin('absen_dosen as ad', 'am.id_jadwal_mahasiswa', '=', 'ad.id_jadwal_dosen')
            ->leftJoin('dosen as d', 'ad.id_dosen', '=', 'd.id')

            // LEFT JOIN ke jadwal_makul (prefix B)
            ->leftJoin('jadwal_makul', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"), '=', 'jadwal_makul.id')
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'B'");
            })
            ->leftJoin('makul as makul_blok', 'jadwal_makul.id_makul', '=', 'makul_blok.id')
            ->leftJoin('ruangan as ruangan_blok', 'jadwal_makul.id_ruangan', '=', 'ruangan_blok.id')

            // LEFT JOIN ke jadwal_metopen (prefix M)
            ->leftJoin('jadwal_metopen', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"), '=', 'jadwal_metopen.id')
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'M'");
            })
            ->leftJoin('makul as makul_metopen', 'jadwal_metopen.id_makul', '=', 'makul_metopen.id')
            ->leftJoin('ruangan as ruangan_metopen', 'jadwal_metopen.id_ruangan', '=', 'ruangan_metopen.id')

            // LEFT JOIN ke tabel materi (dua versi, blok & metopen)
            ->leftJoin('materi as materi_blok', function ($join) {
                $join->on('materi_blok.id_jadwal_blok', '=', DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'B'");
            })
            ->leftJoin('materi as materi_metopen', function ($join) {
                $join->on('materi_metopen.id_jadwal_metopen', '=', DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'M'");
            })

            ->select(
                'am.*',
                'm.nobp',
                'm.nama as nama_mahasiswa',
                'd.nama as nama_dosen',

                // ambil kode & nama makul dari jadwal_makul atau jadwal_metopen
                DB::raw('COALESCE(makul_blok.kode, makul_metopen.kode) as kode_makul'),
                DB::raw('COALESCE(makul_blok.nama, makul_metopen.nama) as nama_makul'),
                DB::raw('COALESCE(ruangan_blok.nama, ruangan_metopen.nama) as ruangan'),
                DB::raw('COALESCE(jadwal_makul.hari, jadwal_metopen.hari) as hari'),

                // ambil materi (judul dan file)
                DB::raw('COALESCE(materi_blok.judul, materi_metopen.judul) as judul_materi'),
                DB::raw('COALESCE(materi_blok.file, materi_metopen.file) as file_materi')
            )
            ->orderBy('am.id', 'DESC')
            ->paginate($entries);

        $absmahasiswa->appends($request->all());

        return view('admin.absensi.mahasiswa.index',['absmahasiswa'=>$absmahasiswa]);
    }

    public function feature(Request $request)
    {
        $query =  DB::table('absen_mahasiswa as am')
            ->join('mahasiswa as m', 'am.id_mahasiswa', '=', 'm.id')
            ->leftJoin('absen_dosen as ad', 'am.id_jadwal_mahasiswa', '=', 'ad.id_jadwal_dosen')
            ->leftJoin('dosen as d', 'ad.id_dosen', '=', 'd.id')

            // LEFT JOIN ke jadwal_makul (prefix B)
            ->leftJoin('jadwal_makul', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"), '=', 'jadwal_makul.id')
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'B'");
            })
            ->leftJoin('makul as makul_blok', 'jadwal_makul.id_makul', '=', 'makul_blok.id')
            ->leftJoin('ruangan as ruangan_blok', 'jadwal_makul.id_ruangan', '=', 'ruangan_blok.id')

            // LEFT JOIN ke jadwal_metopen (prefix M)
            ->leftJoin('jadwal_metopen', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"), '=', 'jadwal_metopen.id')
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'M'");
            })
            ->leftJoin('makul as makul_metopen', 'jadwal_metopen.id_makul', '=', 'makul_metopen.id')
            ->leftJoin('ruangan as ruangan_metopen', 'jadwal_metopen.id_ruangan', '=', 'ruangan_metopen.id')

            // LEFT JOIN ke tabel materi (dua versi, blok & metopen)
            ->leftJoin('materi as materi_blok', function ($join) {
                $join->on('materi_blok.id_jadwal_blok', '=', DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'B'");
            })
            ->leftJoin('materi as materi_metopen', function ($join) {
                $join->on('materi_metopen.id_jadwal_metopen', '=', DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'M'");
            })

            ->select(
                'am.*',
                'm.nobp',
                'm.nama as nama_mahasiswa',
                'd.nama as nama_dosen',

                // ambil kode & nama makul dari jadwal_makul atau jadwal_metopen
                DB::raw('COALESCE(makul_blok.kode, makul_metopen.kode) as kode_makul'),
                DB::raw('COALESCE(makul_blok.nama, makul_metopen.nama) as nama_makul'),
                DB::raw('COALESCE(ruangan_blok.nama, ruangan_metopen.nama) as ruangan'),
                DB::raw('COALESCE(jadwal_makul.hari, jadwal_metopen.hari) as hari'),

                // ambil materi (judul dan file)
                DB::raw('COALESCE(materi_blok.judul, materi_metopen.judul) as judul_materi'),
                DB::raw('COALESCE(materi_blok.file, materi_metopen.file) as file_materi')
            )
            ->orderBy('am.id', 'DESC');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('am.id', 'like', "%{$search}%")
                  ->orWhere('am.tgl', 'like', "%{$search}%")
                  ->orWhere('am.jam_masuk', 'like', "%{$search}%")
                  ->orWhere('am.jam_pulang', 'like', "%{$search}%")
                  ->orWhere('d.nama', 'like', "%{$search}%")
                  ->orWhere('m.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan_blok.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan_metopen.nama', 'like', "%{$search}%")
                  ->orWhere('makul_blok.nama', 'like', "%{$search}%")
                  ->orWhere('makul_metopen.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $absmahasiswa = $query->orderBy('ad.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $absmahasiswa->appends($request->all());

        return view('admin.absensi.mahasiswa.index', compact('absmahasiswa'));
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
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        return view('admin.absensi.mahasiswa.create',['mahasiswa'=>$mahasiswa,'jadmakul'=>$jadmakul]);
    }

    public function create(Request $request){
        // $request->validate([
        //     'tgl' => 'required|string|max:100',
        //     'jam_masuk' => 'required|string|max:100',
        //     'jam_selesai' => 'required|string|max:100',
        //     'id_mahasiswa' => 'required|string|max:100',
        //     'id_jadwal_mahasiswa' => 'required|string|max:100',
        //     'status' => 'required|string|max:100',
        // ]);
        DB::table('absen_mahasiswa')->insert([  
            'tgl' => $request->tgl,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_jadwal_mahasiswa' => $request->id_jadwal_mahasiswa,
            'status' => $request->status,
            'keterangan' => '',
            'qr'=>uniqid()
        ]);

        return redirect('/admin/absmahasiswa')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id)
    {
        $absmahasiswa = DB::table('absen_mahasiswa as am')
            ->join('mahasiswa as m', 'am.id_mahasiswa', '=', 'm.id')
            ->leftJoin('absen_dosen as ad', 'am.id_jadwal_mahasiswa', '=', 'ad.id_jadwal_dosen')
            ->leftJoin('dosen as d', 'ad.id_dosen', '=', 'd.id')

            ->leftJoin('jadwal_makul', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"), '=', 'jadwal_makul.id')
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'B'");
            })
            ->leftJoin('makul as makul_blok', 'jadwal_makul.id_makul', '=', 'makul_blok.id')
            ->leftJoin('ruangan as ruangan_blok', 'jadwal_makul.id_ruangan', '=', 'ruangan_blok.id')

            ->leftJoin('jadwal_metopen', function ($join) {
                $join->on(DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"), '=', 'jadwal_metopen.id')
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'M'");
            })
            ->leftJoin('makul as makul_metopen', 'jadwal_metopen.id_makul', '=', 'makul_metopen.id')
            ->leftJoin('ruangan as ruangan_metopen', 'jadwal_metopen.id_ruangan', '=', 'ruangan_metopen.id')

            ->leftJoin('materi as materi_blok', function ($join) {
                $join->on('materi_blok.id_jadwal_blok', '=', DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'B'");
            })
            ->leftJoin('materi as materi_metopen', function ($join) {
                $join->on('materi_metopen.id_jadwal_metopen', '=', DB::raw("SUBSTRING(am.id_jadwal_mahasiswa, 2)"))
                    ->whereRaw("LEFT(am.id_jadwal_mahasiswa, 1) = 'M'");
            })

            ->select(
                'am.*',
                'm.nobp',
                'm.nama as nama_mahasiswa',
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

        return view('admin.absensi.mahasiswa.edit', [
            'absmahasiswa' => $absmahasiswa
        ]);
    }

    public function update(Request $request, $id) {

        // Jika status = hadir, maka keterangan otomatis "Hadir"
        if ($request->status === 'hadir') {
            $keterangan = 'Hadir';
        } elseif (in_array($request->status, ['izin', 'sakit'])) {
            $keterangan = trim($request->keterangan) ?: '-';
        } else {
            $keterangan = 'Alfa';
        }

        // $request->validate([
        //     'tgl' => 'required|string|max:100',
        //     'jam_masuk' => 'required|string|max:100',
        //     'jam_selesai' => 'required|string|max:100',
        //     'id_mahasiswa' => 'required|string|max:100',
        //     'id_jadwal_mahasiswa' => 'required|string|max:100',
        //     'status' => 'required|string|max:100',
        // ]);
        DB::table('absen_mahasiswa')  
            ->where('id', $id)
            ->update([
            'status' => $request->status,
            'keterangan' => $keterangan,
        ]);

        return redirect('/admin/absmahasiswa')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('absen_mahasiswa')->where('id',$id)->delete();

        return redirect('/admin/absmahasiswa')->with("success","Data Berhasil Dihapus !");
    }
}
