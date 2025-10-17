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

    public function isi($id)
    {
        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
            ->select('absen_dosen.*', 'dosen.nama as nama_dosen')
            ->where('absen_dosen.id', $id)
            ->first();

        if (!$absen) {
            abort(404);
        }

        return view('dosen.absen.isi', compact('absen'));
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
}
