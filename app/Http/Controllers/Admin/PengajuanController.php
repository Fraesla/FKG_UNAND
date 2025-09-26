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
                'mahasiswa.nim',
                'mahasiswa.no_hp'
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
                'mahasiswa.nim',
                'mahasiswa.no_hp'
            );

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pengajuan.id', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nim', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.no_hp', 'like', "%{$search}%")
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

    public function create(Request $request){

         $request->validate([
        'surat_pengajuan' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'krs' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        // tambahkan validasi field lain
        ]);

        // Simpan file ke storage/pengajuan
        $surat = $request->file('surat_pengajuan')->store('pengajuan', 'public');
        $krs = $request->file('krs')->store('pengajuan', 'public');

        DB::table('pengajuan')->insert([  
            'id_mahasiswa' => $request->id_mahasiswa,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'surat_pengajuan' => $surat,
            'judul' => $request->judul,
            'krs' => $krs
        ]);

        return redirect('/admin/pengajuan')->with("success","Data Berhasil Ditambah !");
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
            'id_mahasiswa' => 'required|string|max:255',
            'dosen_pembimbing_1' => 'nullable|string|max:255',
            'dosen_pembimbing_2' => 'nullable|string|max:255',
            'judul' => 'required|string|max:255',

            // file rules
            'surat_pengajuan' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'krs' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
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
