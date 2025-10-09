<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class SeminarHasilController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai join ke tabel mahasiswa
        $seminar_hasil = DB::table('seminar_hasil')
            ->join('mahasiswa', 'seminar_hasil.id_mahasiswa', '=', 'mahasiswa.id')
            ->select(
                'seminar_hasil.*',
                'mahasiswa.nama as nama',
                'mahasiswa.nobp',
            )
            ->orderBy('seminar_hasil.id', 'DESC')
            ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $seminar_hasil->appends($request->all());

        return view('admin.seminarhasil.index', compact('seminar_hasil'));
    } 

    public function feature(Request $request)
    {
        $query = DB::table('seminar_hasil')
        ->join('mahasiswa', 'seminar_hasil.id_mahasiswa', '=', 'mahasiswa.id')
        ->select(
            'seminar_hasil.*',
            'mahasiswa.nama as nama',
            'mahasiswa.nobp',
        );

        // Search berdasarkan field seminar_hasil & mahasiswa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('seminar_hasil.id', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%")
                  ->orWhere('seminar_hasil.dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('seminar_hasil.dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('seminar_hasil.penguji_1', 'like', "%{$search}%")
                  ->orWhere('seminar_hasil.penguji_2', 'like', "%{$search}%")
                  ->orWhere('seminar_hasil.penguji_3', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $seminar_hasil = $query->orderBy('seminar_hasil.id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $seminar_hasil->appends($request->all());

        return view('admin.seminarhasil.index', compact('seminar_hasil'));
    }

    public function add(){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.seminarhasil.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function create(Request $request){
        $request->validate([
        'surat_hasil' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'file_draft' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'bukti_izin' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'lembar_jadwal' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        // tambahkan validasi field lain
        ]);

        // Simpan file ke storage/Seminar Hasil
        $surat = $request->file('surat_hasil')->store('seminar_hasil', 'public');
        $draft = $request->file('file_draft')->store('seminar_hasil', 'public');
        $izin = $request->file('bukti_izin')->store('seminar_hasil', 'public');
        $lembar = $request->file('lembar_jadwal')->store('seminar_hasil', 'public');

        DB::table('seminar_hasil')->insert([  
            'id_mahasiswa' => $request->id_mahasiswa,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'penguji_1' => $request->penguji_1,
            'penguji_2' => $request->penguji_2,
            'penguji_3' => $request->penguji_3,
            'surat_hasil' => $surat,
            'file_draft' => $draft,
            'bukti_izin' => $izin,
            'lembar_jadwal' => $lembar
        ]);

        return redirect('/admin/seminarhasil')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $seminar_hasil = DB::table('seminar_hasil')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        
        return view('admin.seminarhasil.edit',['seminar_hasil'=>$seminar_hasil,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'id_mahasiswa' => 'required|string|max:255',
            'dosen_pembimbing_1' => 'nullable|string|max:255',
            'dosen_pembimbing_2' => 'nullable|string|max:255',
            'penguji_1' => 'nullable|string|max:255',
            'penguji_2' => 'nullable|string|max:255',
            'penguji_3' => 'nullable|string|max:255',

            // file rules
            'surat_hasil' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'file_draft' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'bukti_izin' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'lembar_jadwal' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
        ]);

        // ambil data lama
        $seminar_hasil = DB::table('seminar_hasil')->where('id', $id)->first();

        $updateData = [
            'id_mahasiswa' => $request->id_mahasiswa,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'penguji_1' => $request->penguji_1,
            'penguji_2' => $request->penguji_2,
            'penguji_3' => $request->penguji_3,
        ];

        // === Surat Seminar Proposal ===
        if ($request->hasFile('surat_hasil')) {
            // hapus file lama
            if ($seminar_hasil->surat_hasil && Storage::disk('public')->exists($seminar_hasil->surat_hasil)) {
                Storage::disk('public')->delete($seminar_hasil->surat_hasil);
            }

            // upload file baru
            $path = $request->file('surat_hasil')->store('surat_hasil', 'public');
            $updateData['surat_hasil'] = $path;
        }

        // === File Draft ===
        if ($request->hasFile('file_draft')) {
            // hapus file lama
            if ($seminar_hasil->file_draft && Storage::disk('public')->exists($seminar_hasil->file_draft)) {
                Storage::disk('public')->delete($seminar_hasil->file_draft);
            }

            // upload file baru
            $path = $request->file('file_draft')->store('file_draft', 'public');
            $updateData['file_draft'] = $path;
        }

        // === File Draft ===
        if ($request->hasFile('lembar_jadwal')) {
            // hapus file lama
            if ($seminar_hasil->lembar_jadwal && Storage::disk('public')->exists($seminar_hasil->lembar_jadwal)) {
                Storage::disk('public')->delete($seminar_hasil->lembar_jadwal);
            }

            // upload file baru
            $path = $request->file('lembar_jadwal')->store('lembar_jadwal', 'public');
            $updateData['lembar_jadwal'] = $path;
        }

        // update DB
        DB::table('seminar_hasil')->where('id', $id)->update($updateData);

        return redirect('/admin/seminarhasil')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        // ambil data seminar_hasil
        $seminar_hasil = DB::table('seminar_hasil')->where('id', $id)->first();

        if ($seminar_hasil) {
            // hapus file surat_seminar_hasil kalau ada
            if ($seminar_hasil->surat_hasil && Storage::disk('public')->exists($seminar_hasil->surat_hasil)) {
                Storage::disk('public')->delete($seminar_hasil->surat_hasil);
            }

            // hapus file file_draft kalau ada
            if ($seminar_hasil->file_draft && Storage::disk('public')->exists($seminar_hasil->file_draft)) {
                Storage::disk('public')->delete($seminar_hasil->file_draft);
            }

            // hapus file bukti_izin kalau ada
            if ($seminar_hasil->bukti_izin && Storage::disk('public')->exists($seminar_hasil->bukti_izin)) {
                Storage::disk('public')->delete($seminar_hasil->bukti_izin);
            }

            // hapus file lembar_jadwal kalau ada
            if ($seminar_hasil->lembar_jadwal && Storage::disk('public')->exists($seminar_hasil->lembar_jadwal)) {
                Storage::disk('public')->delete($seminar_hasil->lembar_jadwal);
            }

            // hapus data dari tabel
            DB::table('seminar_hasil')->where('id', $id)->delete();
        }

        return redirect('/admin/seminarhasil')->with("success","Data Berhasil Dihapus !");
    }
}
