<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class PengajuanController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai join ke tabel mahasiswa
        $pengajuan = DB::table('pengajuan')
            ->join('mahasiswa', 'pengajuan.id_mahasiswa', '=', 'mahasiswa.id')
            ->select(
                'pengajuan.*',
                'mahasiswa.nama',
                'mahasiswa.nobp',
            )
            ->orderBy('pengajuan.id', 'DESC')
            ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $pengajuan->appends($request->all());

        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function feature(Request $request)
    {
        $query = DB::table('pengajuan')
            ->join('mahasiswa', 'pengajuan.id_mahasiswa', '=', 'mahasiswa.id')
            ->select(
                'pengajuan.*',
                'mahasiswa.nama',
                'mahasiswa.nobp',
            );

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pengajuan.id', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%")
                  ->orWhere('pengajuan.dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('pengajuan.dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('pengajuan.judul', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $pengajuan = $query->orderBy('pengajuan.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string
        $pengajuan->appends($request->all());

        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function add(){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.pengajuan.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function create(Request $request)
    {
        // ✅ Validasi input — file dibuat opsional (nullable)
        $request->validate([
            'id_mahasiswa'       => 'required|exists:mahasiswa,id',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'judul'              => 'required|string|max:255',
            'surat_pengajuan'    => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'krs'                => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ],[
            'id_mahasiswa.exists'       => 'Mahasiswa yang dipilih tidak valid.',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid.',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid.',
            'judul.required'            => 'Judul wajib diisi.',
        ]);

        // ✅ Siapkan variabel default null
        $surat = '';
        $krs   = '';

        // ✅ Jika user upload file surat_pengajuan
        if ($request->hasFile('surat_pengajuan')) {
            $surat = $request->file('surat_pengajuan')->store('pengajuan', 'public');
        }

        // ✅ Jika user upload file krs
        if ($request->hasFile('krs')) {
            $krs = $request->file('krs')->store('pengajuan', 'public');
        }

        // ✅ Simpan ke database
        DB::table('pengajuan')->insert([
            'id_mahasiswa'       => $request->id_mahasiswa,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'judul'              => $request->judul,
            'surat_pengajuan'    => $surat,
            'krs'                => $krs,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return redirect('/admin/pengajuan')->with("success", "Data Berhasil Ditambah!");
    }

    public function edit($id){
        $pengajuan = DB::table('pengajuan')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        
        return view('admin.pengajuan.edit',['pengajuan'=>$pengajuan,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id)
    {
         $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'surat_pengajuan' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'krs' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'judul' => 'required|string|max:255',
        // tambahkan validasi field lain
        ],[
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid..',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid..',
            'judul.required' => 'Judul wajib diisi.',
        ]);

        // ambil data lama
        $pengajuan = DB::table('pengajuan')->where('id', $id)->first();

        $updateData = [
            'id_mahasiswa' => $request->id_mahasiswa,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'judul' => $request->judul,
        ];

        // === Surat Pengajuan ===
        if ($request->hasFile('surat_pengajuan')) {
            // hapus file lama
            if ($pengajuan->surat_pengajuan && Storage::disk('public')->exists($pengajuan->surat_pengajuan)) {
                Storage::disk('public')->delete($pengajuan->surat_pengajuan);
            }

            // upload file baru
            $path = $request->file('surat_pengajuan')->store('surat_pengajuan', 'public');
            $updateData['surat_pengajuan'] = $path;
        }

        // === KRS ===
        if ($request->hasFile('krs')) {
            // hapus file lama
            if ($pengajuan->krs && Storage::disk('public')->exists($pengajuan->krs)) {
                Storage::disk('public')->delete($pengajuan->krs);
            }

            // upload file baru
            $path = $request->file('krs')->store('krs', 'public');
            $updateData['krs'] = $path;
        }

        // update DB
        DB::table('pengajuan')->where('id', $id)->update($updateData);

        return redirect('/admin/pengajuan')->with("success", "Data Berhasil Diupdate !");
    } 
    public function delete($id)
    {
         // ambil data pengajuan
        $pengajuan = DB::table('pengajuan')->where('id', $id)->first();

        if ($pengajuan) {
            // hapus file surat_pengajuan kalau ada
            if ($pengajuan->surat_pengajuan && Storage::disk('public')->exists($pengajuan->surat_pengajuan)) {
                Storage::disk('public')->delete($pengajuan->surat_pengajuan);
            }

            // hapus file krs kalau ada
            if ($pengajuan->krs && Storage::disk('public')->exists($pengajuan->krs)) {
                Storage::disk('public')->delete($pengajuan->krs);
            }

            // hapus data dari tabel
            DB::table('pengajuan')->where('id', $id)->delete();
        }

        return redirect('/admin/pengajuan')->with("success", "Data dan file berhasil dihapus!");
    }
}
