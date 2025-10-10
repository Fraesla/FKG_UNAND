<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    public function index()
    {
        $dosen = DB::table('dosen')->orderBy('nama', 'ASC')->get();

        $absen = DB::table('absen_dosen')
            ->join('dosen', 'absen_dosen.id_dosen', '=', 'dosen.id')
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
            ->get();

        // 🔄 Format data sesuai kebutuhan FullCalendar
        $events = $absen->map(function ($row) {
            return [
                'id'    => $row->id,
                'title' => "{$row->nama_dosen} (" . ucfirst($row->status) . ")",
                'start' => "{$row->tgl}T{$row->jam_masuk}",
                'end'   => "{$row->tgl}T{$row->jam_pulang}",
                'color' => $row->status === 'belum absen' ? '#dc3545' : '#198754', // merah/hijau
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

        return redirect()
            ->route('dosen.absen.isi', $id)
            ->with('success', 'Absen berhasil disimpan dan QR Code telah diperbarui!');
    }
}
