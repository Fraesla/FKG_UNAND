<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\MateriExport;
use App\Imports\MateriImport;
use Auth;

class MateriController extends Controller
{
    public function read(Request $request)
    {
        $entries = $request->input('entries', 5);

        // Ambil data materi + relasi jadwal makul (id_jadwal_blok dan id_jadwal_metopen)
        $materi = DB::table('materi')
            // Relasi ke jadwal_makul lewat id_jadwal_blok
            ->leftJoin('jadwal_makul as blok', 'materi.id_jadwal_blok', '=', 'blok.id')
            ->leftJoin('kelas as kelas_blok', 'blok.id_kelas', '=', 'kelas_blok.id')
            ->leftJoin('makul as makul_blok', 'blok.id_makul', '=', 'makul_blok.id')
            ->leftJoin('dosen as dosen_blok', 'blok.id_dosen', '=', 'dosen_blok.id')
            ->leftJoin('ruangan as ruangan_blok', 'blok.id_ruangan', '=', 'ruangan_blok.id')

            // Relasi ke jadwal_makul lewat id_jadwal_metopen
            ->leftJoin('jadwal_metopen as metopen', 'materi.id_jadwal_metopen', '=', 'metopen.id')
            ->leftJoin('makul as makul_metopen', 'metopen.id_makul', '=', 'makul_metopen.id')
            ->leftJoin('dosen as dosen_metopen', 'metopen.id_dosen', '=', 'dosen_metopen.id')
            ->leftJoin('ruangan as ruangan_metopen', 'metopen.id_ruangan', '=', 'ruangan_metopen.id')

            ->select(
                'materi.*',

                // Relasi blok
                'blok.hari as blok_hari',
                'blok.minggu as blok_minggu',
                'blok.tgl as blok_tgl',
                'blok.jam_mulai as blok_jam_mulai',
                'blok.jam_selesai as blok_jam_selesai',
                'kelas_blok.nama as blok_kelas',
                'makul_blok.nama as blok_makul',
                'dosen_blok.nama as blok_dosen',
                'ruangan_blok.nama as blok_ruangan',

                // Relasi metopen
                'metopen.hari as metopen_hari',
                'metopen.minggu as metopen_minggu',
                'metopen.tgl as metopen_tgl',
                'metopen.jam_mulai as metopen_jam_mulai',
                'metopen.jam_selesai as metopen_jam_selesai',
                'makul_metopen.nama as metopen_makul',
                'dosen_metopen.nama as metopen_dosen',
                'ruangan_metopen.nama as metopen_ruangan'
            )
            ->orderBy('materi.id', 'DESC')
            ->paginate($entries);

        // Pagination tetap bawa query string
        $materi->appends($request->all());
        $username = auth()->user()->username;

        // Ambil data jadwal untuk dropdown/filter
        $jadwal = DB::table('jadwal_makul')
            ->join('kelas', 'jadwal_makul.id_kelas', '=', 'kelas.id')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('dosen', 'jadwal_makul.id_dosen', '=', 'dosen.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'kelas.nama as kelas',
                'jadwal_makul.minggu',
                'jadwal_makul.tgl',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as makul',
                'dosen.nama as dosen',
                'ruangan.nama as ruangan'
            )
            ->orderBy('jadwal_makul.id', 'DESC')
            ->get();

        return view('admin.master.materi.index', [
            'materi' => $materi,
            'jadwal' => $jadwal,
            'username' => $username
        ]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('materi');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $materi = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $materi->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.master.materi.index', compact('materi','username'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new materiImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Materi berhasil diimport!');
    }

    public function add(){
        $jadwalBlok = DB::table('jadwal_makul')->get();
        $jadwalMetopen = DB::table('jadwal_metopen')->get();
        return view('admin.master.materi.create', compact('jadwalBlok', 'jadwalMetopen'));
    }

    public function create(Request $request){
        $request->validate([
            'id_jawdal_blok' => 'nullable|string|max:100',
            'id_jawdal_metopen' => 'nullable|string|max:100',
            'judul' => 'required|string|max:100',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // maksimal 2MB
        ],[
            'judul.required' => 'Nama Judul Materi wajib diisi.',
        ]);

        $path = null;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('file_materi', 'public');
        }

        DB::table('materi')->insert([  
            'judul' => $request->judul,
            'file' => $path, // bisa null jika tidak diunggah
        ]);

        return redirect('/admin/materi')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $materi = DB::table('materi')->where('id',$id)->first();
        
        return view('admin.master.materi.edit',['materi'=>$materi]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:100',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048', // maksimal 2MB
        ], [
            'judul.required' => 'Nama Judul Materi wajib diisi.',
        ]);

        // 🔹 Ambil data materi dari database
        $materi = DB::table('materi')->where('id', $id)->first();

        if (!$materi) {
            return redirect()->back()->with('error', 'Data materi tidak ditemukan!');
        }

        $dataUpdate = ['judul' => $request->judul];

        // 🔹 Jika upload file baru
        if ($request->hasFile('file')) {
            // Hapus file lama (jika ada)
            if (!empty($materi->file) && file_exists(storage_path('app/public/' . $materi->file))) {
                unlink(storage_path('app/public/' . $materi->file));
            }

            // Simpan file baru
            $path = $request->file('file')->store('file_materi', 'public');
            $dataUpdate['file'] = $path;
        }

        // 🔹 Update ke database
        DB::table('materi')->where('id', $id)->update($dataUpdate);

        return redirect('/admin/materi')->with('success', 'Data Berhasil Diupdate!');
    }

    public function delete($id)
    {
        // ambil data materi
        $materi = DB::table('materi')->where('id', $id)->first();

        if ($materi) {
            // hapus file materi kalau ada
            if ($materi->file && Storage::disk('public')->exists($materi->file)) {
                Storage::disk('public')->delete($materi->file);
            }

            // hapus data dari tabel
            DB::table('materi')->where('id', $id)->delete();
        }

        return redirect('/admin/materi')->with("success","Data Berhasil Dihapus !");
    }
}
