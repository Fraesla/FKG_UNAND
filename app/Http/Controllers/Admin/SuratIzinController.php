<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SuratIzinController extends Controller
{
    public function read(Request $request, $id_prodi = null)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query dengan join ke tabel mahasiswa
        $query = DB::table('surat_izin')
            ->join('mahasiswa', 'surat_izin.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('prodi', 'surat_izin.id_prodi', '=', 'prodi.id')
            ->select(
                'surat_izin.*',
                'prodi.nama as prodi',
                'mahasiswa.nama',
                'mahasiswa.nobp'
            )
            ->orderBy('surat_izin.id', 'DESC');

         // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('surat_izin.id_prodi', $id_prodi);
        }

        // Supaya pagination tetap bawa query string (search / entries)
        $suratizin = $query->paginate($entries);
        $suratizin->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.suratizin.index', compact('suratizin','username','id_prodi'));
    }

    public function feature(Request $request, $id_prodi = null)
    {
        $query = DB::table('surat_izin')
        ->join('mahasiswa', 'surat_izin.id_mahasiswa', '=', 'mahasiswa.id')
        ->join('prodi', 'surat_izin.id_prodi', '=', 'prodi.id')
        ->select(
            'surat_izin.*',
            'prodi.nama as prodi',
            'mahasiswa.nama',
            'mahasiswa.nobp'
        );

         // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('surat_izin.id_prodi', $id_prodi);
        }

        // 🔍 Fitur search
        if ($request->filled('search')) 
        {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('surat_izin.id', 'like', "%{$search}%")
                  ->orWhere('surat_izin.jenis', 'like', "%{$search}%")
                  ->orWhere('surat_izin.judul_penelitian', 'like', "%{$search}%")
                  ->orWhere('surat_izin.dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('surat_izin.dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('surat_izin.isi_surat', 'like', "%{$search}%")
                  // 🔽 Field dari tabel mahasiswa
                  ->orWhere('prodi.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%");
            });
        }

    // Show entries (default 10)
    $entries = $request->get('entries', 10);

    // Ambil data dengan pagination
    $suratizin = $query->orderBy('surat_izin.id', 'DESC')->paginate($entries);

    // Supaya pagination tetap bawa query string
    $suratizin->appends($request->all());
    $username = auth()->user()->username;

    return view('admin.suratizin.index', compact('suratizin','username','id_prodi'));
    } 

    public function add($id_prodi = null){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.suratizin.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen,'id_prodi'=>$id_prodi]);
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'jenis' => 'required|string|max:255',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'judul_penelitian' => 'required|string|max:255',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'isi_surat' => 'nullable|string',
        ],[
            'jenis.required' => 'Jenis wajib diisi.',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'judul_penelitian.required' => 'Judul Penelitian wajib diisi.',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid..',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid..',
        ]);
        DB::table('surat_izin')->insert([  
            'jenis' => $request->jenis,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_prodi' => $request->id_prodi,
            'judul_penelitian' => $request->judul_penelitian,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'isi_surat' => $request->isi_surat
        ]);

        return redirect('/admin/suratizin/'.$request->id_prodi)->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $suratizin = DB::table('surat_izin')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.suratizin.edit',['suratizin'=>$suratizin,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        // Validasi input
        $request->validate([
            'jenis' => 'required|string|max:255',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'judul_penelitian' => 'required|string|max:255',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'isi_surat' => 'nullable|string',
        ],[
            'jenis.required' => 'Jenis wajib diisi.',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'judul_penelitian.required' => 'Judul Penelitian wajib diisi.',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid..',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid..',
        ]);
        DB::table('surat_izin')  
            ->where('id', $id)
            ->update([
            'jenis' => $request->jenis,
            'id_mahasiswa' => $request->id_mahasiswa,
            'judul_penelitian' => $request->judul_penelitian,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'isi_surat' => $request->isi_surat
        ]);

        return redirect('/admin/suratizin/'.$request->id_prodi)->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        // Ambil data berdasarkan ID
        $suratizin = DB::table('surat_izin')->where('id', $id)->first();

        if (!$suratizin) {
            return redirect()->back()->with('error', 'Data Surat Izin Penelitian tidak ditemukan!');
        }

        // Hapus jadwal
        DB::table('surat_izin')->where('id', $id)->delete();

        return redirect('/admin/suratizin/'.$suratizin->id_prodi)->with("success","Data Berhasil Dihapus !");
    }
}
