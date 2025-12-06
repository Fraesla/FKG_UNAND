<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')

            // LEFT JOIN ke jadwal_makul berdasarkan prefix "B"
            ->leftJoin('jadwal_makul', function ($join) {
                $join->on(DB::raw("SUBSTRING(absen_dosen.id_jadwal_dosen, 2)"), '=', 'jadwal_makul.id')
                     ->whereRaw("LEFT(absen_dosen.id_jadwal_dosen, 1) = 'B'");
            })
            ->leftJoin('makul as makul_makul', 'jadwal_makul.id_makul', '=', 'makul_makul.id')

            // LEFT JOIN ke jadwal_metopen berdasarkan prefix "M"
            ->leftJoin('jadwal_metopen', function ($join) {
                $join->on(DB::raw("SUBSTRING(absen_dosen.id_jadwal_dosen, 2)"), '=', 'jadwal_metopen.id')
                     ->whereRaw("LEFT(absen_dosen.id_jadwal_dosen, 1) = 'M'");
            })
            ->leftJoin('makul as makul_metopen', 'jadwal_metopen.id_makul', '=', 'makul_metopen.id')

            ->select(
                'absen_dosen.id',
                'absen_dosen.id_dosen',
                'absen_dosen.id_jadwal_dosen',
                'absen_dosen.tgl',
                'absen_dosen.jam_masuk',
                'absen_dosen.jam_pulang',
                'absen_dosen.status',
                'absen_dosen.keterangan',
                'absen_dosen.qr',
                'dosen.nama as nama_dosen',
                DB::raw('COALESCE(makul_makul.kode, makul_metopen.kode) as nama_makul')
            )
            ->orderBy('absen_dosen.tgl', 'asc')
            ->get();

        $events = $absen->map(function ($row) {
            return [
                'id'    => $row->id,
                'title' => "Kode Mata Kuliah :{$row->nama_makul} (Dosen : {$row->nama_dosen})",
                'start' => date('Y-m-d', strtotime($row->tgl)) . "T" . ($row->jam_masuk ?? '00:00:00'),
                'end'   => date('Y-m-d', strtotime($row->tgl)) . "T" . ($row->jam_pulang ?? '23:59:59'),
                'extendedProps' => [
                    'id'         => $row->id,
                    'id_dosen'   => $row->id_dosen,
                    'keterangan' => $row->keterangan,
                    'tgl'        => $row->tgl,
                    'jam_masuk'  => $row->jam_masuk,
                    'jam_pulang' => $row->jam_pulang,
                    'qr'         => $row->qr,
                ],
            ];
        });

        $username = auth()->user()->username;
        $mahasiswa = DB::table('mahasiswa')->where('nobp', $username)->first();

        return view('mahasiswa.absen.index', [
            'events' => $events,
            'mahasiswa' => $mahasiswa
        ]);
    } 

    public function isi($id)
    {
        // Ambil data absen seperti biasa
        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')

            // LEFT JOIN ke jadwal_makul (prefix B)
            ->leftJoin('jadwal_makul', function ($join) {
                $join->on(DB::raw("SUBSTRING(absen_dosen.id_jadwal_dosen, 2)"), '=', 'jadwal_makul.id')
                    ->whereRaw("LEFT(absen_dosen.id_jadwal_dosen, 1) = 'B'");
            })
            ->leftJoin('makul as makul_makul', 'jadwal_makul.id_makul', '=', 'makul_makul.id')
            ->leftJoin('ruangan as ruangan_blok', 'jadwal_makul.id_ruangan', '=', 'ruangan_blok.id')

            // LEFT JOIN ke jadwal_metopen (prefix M)
            ->leftJoin('jadwal_metopen', function ($join) {
                $join->on(DB::raw("SUBSTRING(absen_dosen.id_jadwal_dosen, 2)"), '=', 'jadwal_metopen.id')
                    ->whereRaw("LEFT(absen_dosen.id_jadwal_dosen, 1) = 'M'");
            })
            ->leftJoin('makul as makul_metopen', 'jadwal_metopen.id_makul', '=', 'makul_metopen.id')
            ->leftJoin('ruangan as ruangan_metopen', 'jadwal_metopen.id_ruangan', '=', 'ruangan_metopen.id')

            // LEFT JOIN ke materi
            ->leftJoin('materi', 'materi.id_absen_dosen', '=', 'absen_dosen.id')

            ->select(
                'absen_dosen.id',
                'absen_dosen.id_dosen',
                'absen_dosen.id_jadwal_dosen',
                'absen_dosen.tgl',
                'absen_dosen.jam_masuk',
                'absen_dosen.jam_pulang',
                'absen_dosen.status',
                'absen_dosen.keterangan',
                'absen_dosen.qr',
                'dosen.nama as nama_dosen',
                DB::raw('COALESCE(makul_makul.kode, makul_metopen.kode) as kode_makul'),
                DB::raw('COALESCE(makul_makul.nama, makul_metopen.nama) as nama_makul'),
                DB::raw('COALESCE(ruangan_blok.nama, ruangan_metopen.nama) as ruangan'),
                DB::raw('COALESCE(jadwal_makul.hari, jadwal_metopen.hari) as hari'),
                'materi.judul as judul_materi',
                'materi.file as file_materi'
            )
            ->where('absen_dosen.id', $id)
            ->first();

        if (!$absen) {
            abort(404);
        }

        $username = auth()->user()->username;
        $mahasiswa = DB::table('mahasiswa')->where('nobp', $username)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini.');
        }

        return view('mahasiswa.absen.absen', compact('absen', 'mahasiswa'));
    }

    public function absen(Request $request)
    {

        $username = auth()->user()->username;
        $mahasiswa = DB::table('mahasiswa')->where('nobp', $username)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini.');
        }
        // Cek apakah mahasiswa sudah pernah absen untuk pertemuan ini
        $sudahAbsen = DB::table('absen_mahasiswa')
            ->where('id_mahasiswa', $mahasiswa->id)
            ->where('id_jadwal_mahasiswa', $request->id_jadwal_mahasiswa)
            ->exists();

        if ($sudahAbsen) {
            return back()->with('warning', 'Kamu sudah mengisi absensi untuk pertemuan ini!');
        }

        // Tentukan keterangan otomatis
        $keterangan = '';
        if ($request->status === 'hadir') {
            $keterangan = 'Hadir';
        } elseif (in_array($request->status, ['izin', 'sakit'])) {
            // Ambil dari input user jika izin/sakit
            $keterangan = $request->keterangan ?? '';
        }
        else{
            $keterangan = 'Alfa';
        }

        // Validasi input
            $request->validate([
                'status' => 'required|exists:status',
            ],[
                'status.required' => 'Status yang dipilih tidak valid..',
                'status.exists' => 'Status yang dipilih tidak valid..',
            ]);

        // Simpan data absensi mahasiswa
        DB::table('absen_mahasiswa')->insert([
            'tgl' => $request->tgl,
            'jam_masuk'   => $request->jam_masuk,
            'jam_pulang'   => $request->jam_pulang,
            'id_mahasiswa'   => $mahasiswa->id,
            'id_jadwal_mahasiswa'   => $request->id_jadwal_mahasiswa,
            'status'         => $request->status,
            'keterangan'     => $keterangan
        ]);

        return redirect('/mahasiswa/absensi/mahasiswa')->with('success', 'Absensi berhasil disimpan!');
    } 

    public function mahasiswa(Request $request)
    {
        $entries = $request->input('entries', 5);
        $user = Auth::user();

        // Cari mahasiswa berdasarkan username (nobp)
        $mahasiswa = DB::table('mahasiswa')
            ->where('nobp', $user->username)
            ->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan untuk user ini.');
        }

        $absen = DB::table('absen_mahasiswa as am')
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
            ->where('am.id_mahasiswa', $mahasiswa->id)
            ->orderBy('am.id', 'DESC')
            ->paginate($entries);

        $absen->appends($request->all());

        return view('mahasiswa.absen.mahasiswa', compact('absen'));
    }

    public function feature(Request $request)
    {
        $username = auth()->user()->username;
        $mahasiswa = DB::table('mahasiswa')->where('nobp', $username)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan untuk akun ini.');
        }

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
            ->where('am.id_mahasiswa', $mahasiswa->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('am.id', 'like', "%{$search}%")
                  ->orWhere('am.tgl', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.hari', 'like', "%{$search}%")
                  ->orWhere('am.jam_masuk', 'like', "%{$search}%")
                  ->orWhere('am.jam_pulang', 'like', "%{$search}%")
                  ->orWhere('m.nama', 'like', "%{$search}%")
                  ->orWhere('d.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan_blok.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan_metopen.nama', 'like', "%{$search}%")
                  ->orWhere('makul_blok.kode', 'like', "%{$search}%")
                  ->orWhere('makul_blok.nama', 'like', "%{$search}%")
                  ->orWhere('makul_metopen.kode', 'like', "%{$search}%")
                  ->orWhere('makul_metopen.nama', 'like', "%{$search}%")
                  ->orWhere('materi_blok.judul', 'like', "%{$search}%")
                  ->orWhere('materi_metopen.judul', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $absen = $query->orderBy('am.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $absen->appends($request->all());

        return view('mahasiswa.absen.mahasiswa', compact('absen'));
    }
}
