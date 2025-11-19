<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function index()
    {
        $username = auth()->user()->username;
        $dosen = DB::table('dosen')->where('nip', $username)->first();

        if (!$dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan untuk akun ini.');
        }

        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->where('absen_dosen.id_dosen', $dosen->id)
            ->select(
                'absen_dosen.id',
                'absen_dosen.id_dosen',
                'absen_dosen.tgl',
                'absen_dosen.jam_masuk',
                'absen_dosen.jam_pulang',
                'absen_dosen.status',
                'absen_dosen.keterangan',
                'dosen.nama as nama_dosen'
            )
            ->orderBy('absen_dosen.tgl', 'asc')
            ->get();

        $events = $absen->map(function ($row) {
            return [
                'id'    => $row->id,
                'title' => "{$row->nama_dosen} (" . ucfirst($row->status) . ")",
                'start' => date('Y-m-d', strtotime($row->tgl)) . "T" . ($row->jam_masuk ?? '00:00:00'),
                'end'   => date('Y-m-d', strtotime($row->tgl)) . "T" . ($row->jam_pulang ?? '23:59:59'),
                'color' => $row->status === 'belum absen' ? '#dc3545' : '#198754',
                'extendedProps' => [
                    'id_dosen'   => $row->id_dosen,
                    'status'     => $row->status,
                    'keterangan' => $row->keterangan,
                    'tgl'        => $row->tgl,
                    'jam_masuk'  => $row->jam_masuk,
                    'jam_pulang' => $row->jam_pulang,
                ],
            ];
        });

        return view('dosen.absen.kalender', [
            'events' => $events,
            'dosen'  => $dosen,
        ]);
    }

    public function isi(Request $request, $id)
    {
        $entries = $request->input('entries', 5);
        // DETAIL ABSEN DOSEN
        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->select('absen_dosen.*', 'dosen.nama as nama_dosen')
            ->where('absen_dosen.id', $id)
            ->first();

        if (!$absen) {
            abort(404);
        }

        // DAFTAR ABSEN MAHASISWA BERDASARKAN JADWAL DOSEN
        $absenMahasiswa = DB::table('absen_mahasiswa as am')
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
            ->where('am.id_jadwal_mahasiswa', '=', $absen->id_jadwal_dosen)
            ->where('am.tgl', '=', $absen->tgl)
            ->paginate($entries);

        return view('dosen.absen.isi', compact('absen', 'absenMahasiswa'));
    }

    public function absen($id)
    {
        $absen = DB::table('absen_dosen')->where('id', $id)->first();

        if (!$absen) {
            abort(404);
        }

        // Update status dan generate QR Code unik
        $qrCode = uniqid('qr_');

        DB::table('absen_dosen')
            ->where('id', $id)
            ->update([
                'status' => 'hadir',
                'qr' => $qrCode,
            ]);


        // 🔁 Setelah update, langsung tampilkan halaman isi()
        session()->flash('success', 'Absen berhasil disimpan dan QR Code telah diperbarui!');

        return $this->isi($id); // langsung panggil method isi() di controller yang sama
    } 

    public function materi(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'file'  => 'required|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        // 🔹 Ambil data absen dosen
        $absen = DB::table('absen_dosen')->where('id', $id)->first();

        if (!$absen) {
            return response()->json([
                'success' => false,
                'message' => 'Data absen dosen tidak ditemukan.',
            ], 404);
        }

        // 🔹 Cek prefix dari id_jadwal_dosen (contoh: B3 → jadwal_makul, M2 → jadwal_metopen)
        $prefix = strtoupper(substr($absen->id_jadwal_dosen, 0, 1));
        $jadwalId = (int) preg_replace('/[^0-9]/', '', $absen->id_jadwal_dosen);

        $jadwalBlok = null;
        $jadwalMetopen = null;

        if ($prefix === 'B') {
            $jadwalBlok = DB::table('jadwal_makul')->where('id', $jadwalId)->first();
        } elseif ($prefix === 'M') {
            $jadwalMetopen = DB::table('jadwal_metopen')->where('id', $jadwalId)->first();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Format ID jadwal tidak dikenali. Harus diawali dengan B (Blok) atau M (Metopen).',
            ], 400);
        }

        // 🔹 Jika tidak ditemukan di kedua tabel
        if (!$jadwalBlok && !$jadwalMetopen) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ditemukan jadwal yang sesuai di tabel jadwal_makul atau jadwal_metopen.',
            ], 404);
        }

        // 🔹 Upload file materi ke storage/public/materi
        $path = $request->file('file')->store('materi', 'public');

        // 🔹 Data materi yang akan disimpan
        $dataMateri = [
            'id_absen_dosen' => $absen->id,
            'judul'          => $request->judul,
            'file'           => $path,
            'updated_at'     => now(),
        ];

        if ($jadwalBlok) {
            $dataMateri['id_jadwal_blok'] = $jadwalBlok->id;
            $dataMateri['id_jadwal_metopen'] = '';
        } elseif ($jadwalMetopen) {
            $dataMateri['id_jadwal_metopen'] = $jadwalMetopen->id;
            $dataMateri['id_jadwal_blok'] = '';
        }

        // 🔹 Cek apakah materi sudah ada berdasarkan jadwal
        $materiExist = DB::table('materi')
            ->where(function ($query) use ($jadwalBlok, $jadwalMetopen) {
                if ($jadwalBlok) {
                    $query->where('id_jadwal_blok', $jadwalBlok->id);
                } elseif ($jadwalMetopen) {
                    $query->where('id_jadwal_metopen', $jadwalMetopen->id);
                }
            })
            ->first();

        // 🔹 Jika sudah ada → update, kalau belum → insert
        if ($materiExist) {
            DB::table('materi')->where('id', $materiExist->id)->update($dataMateri);
            $action = 'update';
        } else {
            $dataMateri['created_at'] = now();
            DB::table('materi')->insert($dataMateri);
            $action = 'insert';
        }

        // 🔹 Kirim response sukses
        return response()->json([
            'success' => true,
            'message' => $action === 'update'
                ? 'Materi berhasil diperbarui.'
                : 'Materi berhasil diupload.',
            'data' => [
                'judul' => $request->judul,
                'file'  => asset('storage/' . $path),
            ],
        ]);
    }

    public function feature(Request $request, $id)
    {
        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->select('absen_dosen.*', 'dosen.nama as nama_dosen')
            ->where('absen_dosen.id', $id)
            ->first();

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
            ->orderBy('am.id', 'DESC')
            ->where('am.id_jadwal_mahasiswa', '=', $absen->id_jadwal_dosen)
            ->where('am.tgl', '=', $absen->tgl);

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
                  ->orWhere('makul_metopen.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $absenMahasiswa = $query->orderBy('am.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $absenMahasiswa->appends($request->all());

        return view('dosen.absen.isi', compact('absen','absenMahasiswa'));
    }

    public function add($id)
    {
        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->select('absen_dosen.*', 'dosen.nama as nama_dosen')
            ->where('absen_dosen.id', $id)
            ->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        return view('dosen.absen.create',['mahasiswa'=>$mahasiswa,'absen'=>$absen]);
    }

    public function create(Request $request, $id){

        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->select('absen_dosen.*', 'dosen.nama as nama_dosen')
            ->where('absen_dosen.id', $id)
            ->first();

          // Validasi input
            $request->validate([
                'id_mahasiswa' => 'required|exists:id_mahasiswa,id',
                'status' => 'required|exists:status',
            ],[
                'id_mahasiswa.required' => 'Nama Mahasiswa yang dipilih tidak valid..',
                'id_mahasiswa.exists' => 'Nama Mahasiswa yang dipilih tidak valid..',
                'status.required' => 'Status yang dipilih tidak valid..',
                'status.exists' => 'Status yang dipilih tidak valid..',
            ]);

        DB::table('absen_mahasiswa')->insert([  
            'tgl' => $request->tgl,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_jadwal_mahasiswa' => $request->id_jadwal_mahasiswa,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect('/dosen/absendosen/isi/'.$absen->id)->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id)
    {
        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->select('absen_dosen.*', 'dosen.nama as nama_dosen')
            ->where('absen_dosen.id', $id)
            ->first();
        $absenMhs = DB::table('absen_mahasiswa')
            ->where('id', $id)   // sesuaikan id yang kamu kirim
            ->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        return view('dosen.absen.edit',['mahasiswa'=>$mahasiswa, 'absenMhs'=>$absenMhs, 'absen'=>$absen]);
    }

    public function update(Request $request, $id) {

         // Validasi input
            $request->validate([
                'id_mahasiswa' => 'required|exists:id_mahasiswa,id',
                'status' => 'required|exists:status',
            ],[
                'id_mahasiswa.required' => 'Nama Mahasiswa yang dipilih tidak valid..',
                'id_mahasiswa.exists' => 'Nama Mahasiswa yang dipilih tidak valid..',
                'status.required' => 'Status yang dipilih tidak valid..',
                'status.exists' => 'Status yang dipilih tidak valid..',
            ]);

        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->select('absen_dosen.*', 'dosen.nama as nama_dosen')
            ->where('absen_dosen.id', $id)
            ->first();
        
        DB::table('absen_mahasiswa')  
            ->where('id', $id)
            ->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect('/dosen/absendosen')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('absen_mahasiswa')->where('id',$id)->delete();

        return redirect('/dosen/absendosen')->with("success","Data Berhasil Dihapus !");
    }
}
