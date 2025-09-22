<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SuratAktifKuliahController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $surataktifkuliah = DB::table('surat_aktif_kuliah')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $surataktifkuliah->appends($request->all());

        return view('admin.surataktifkuliah.index', ['surataktifkuliah' => $surataktifkuliah]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('surat_aktif_kuliah');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('pango', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('nama_mhs', 'like', "%{$search}%")
                  ->orWhere('tgl_lahir', 'like', "%{$search}%")
                  ->orWhere('no_bp', 'like', "%{$search}%")
                  ->orWhere('semester', 'like', "%{$search}%")
                  ->orWhere('tahun_akademik', 'like', "%{$search}%")
                  ->orWhere('nama_ort', 'like', "%{$search}%")
                  ->orWhere('tmp_lahir_ort', 'like', "%{$search}%")
                  ->orWhere('tgl_lahir_ort', 'like', "%{$search}%")
                  ->orWhere('nip_ort', 'like', "%{$search}%")
                  ->orWhere('pango_ort', 'like', "%{$search}%")
                  ->orWhere('jabatan_ort', 'like', "%{$search}%")
                  ->orWhere('instansi_ort', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $surataktifkuliah = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $surataktifkuliah->appends($request->all());

        return view('admin.surataktifkuliah.index', compact('surataktifkuliah'));
    }

    public function add(){
        return view('admin.surataktifkuliah.create');
    }

    public function create(Request $request){
        DB::table('surat_aktif_kuliah')->insert([  
            'nama' => $request->nama,
            'nip' => $request->nip,
            'pango' => $request->pango,
            'jabatan' => $request->jabatan,
            'nama_mhs' => $request->nama_mhs,
            'tmp_lahir_mhs' => $request->tmp_lahir_mhs,
            'tgl_lahir_mhs' => $request->tgl_lahir_mhs,
            'no_bp' => $request->no_bp,
            'semester' => $request->semester,
            'tahun_akademik' => $request->tahun_akademik,
            'nama_ort' => $request->nama_ort,
            'tmp_lahir_ort' => $request->tmp_lahir_ort,
            'tgl_lahir_ort' => $request->tgl_lahir_ort,
            'nip_ort' => $request->nip_ort,
            'pango_ort' => $request->pango_ort,
            'jabatan_ort' => $request->jabatan_ort,
            'instansi_ort' => $request->instansi_ort
        ]);

        return redirect('/admin/surataktifkuliah')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $surataktifkuliah = DB::table('surat_aktif_kuliah')->where('id',$id)->first();
        
        return view('admin.surataktifkuliah.edit',['surataktifkuliah'=>$surataktifkuliah]);
    }

    public function update(Request $request, $id) {
        DB::table('surat_aktif_kuliah')  
            ->where('id', $id)
            ->update([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'pango' => $request->pango,
            'jabatan' => $request->jabatan,
            'nama_mhs' => $request->nama_mhs,
            'tmp_lahir_mhs' => $request->tmp_lahir_mhs,
            'tgl_lahir_mhs' => $request->tgl_lahir_mhs,
            'no_bp' => $request->no_bp,
            'semester' => $request->semester,
            'tahun_akademik' => $request->tahun_akademik,
            'nama_ort' => $request->nama_ort,
            'tmp_lahir_ort' => $request->tmp_lahir_ort,
            'tgl_lahir_ort' => $request->tgl_lahir_ort,
            'nip_ort' => $request->nip_ort,
            'pango_ort' => $request->pango_ort,
            'jabatan_ort' => $request->jabatan_ort,
            'instansi_ort' => $request->instansi_ort
        ]);

        return redirect('/admin/surataktifkuliah')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('surat_aktif_kuliah')->where('id',$id)->delete();

        return redirect('/admin/surataktifkuliah')->with("success","Data Berhasil Dihapus !");
    }
}
