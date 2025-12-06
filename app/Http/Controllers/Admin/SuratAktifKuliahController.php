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
        $username = auth()->user()->username;

        return view('admin.surataktifkuliah.index', ['surataktifkuliah' => $surataktifkuliah,'username'=> $username]);
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
        $username = auth()->user()->username;

        return view('admin.surataktifkuliah.index', compact('surataktifkuliah','username'));
    }

    public function add(){
        $mahasiswa = DB::table('mahasiswa')
        ->join('tahun_ajaran', 'mahasiswa.id_tahun_ajaran', '=', 'tahun_ajaran.id')
        ->select(
            'mahasiswa.*',
            'tahun_ajaran.nama as tahun_ajaran'
        )
        ->orderBy('mahasiswa.id', 'DESC')
        ->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.surataktifkuliah.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function create(Request $request){
        // Validasi
        $request->validate([
            'dosen_data' => 'required|exists:dosen_data',
            'mahasiswa_data' => 'required|exists:mahasiswa_data',
            'tmp_lahir_mhs' => 'required|string|max:255',
            'tgl_lahir_mhs' => 'required|string|max:255',
            'nama_ort' => 'required|string|max:255',
            'tmp_lahir_ort' => 'required|string|max:255',
            'tgl_lahir_ort' => 'required|string|max:255',
            'nip_ort' => 'required|string|max:255',
            'pango_ort' => 'required|string|max:255',
            'jabatan_ort' => 'required|string|max:255',
            'instansi_ort' => 'required|string|max:255',
        ],[
            'dosen_data.required'            => 'Silahkan Pilih dulu data dosen terlebih dahulu.',
            'dosen_data.exists'            => 'Pilihan dulu data dosen tidak valid.',
            'mahasiswa_data.required'            => 'Silahkan Pilih dulu data mahasiswa terlebih dahulu.',
            'mahasiswa_data.exists'            => 'Pilihan dulu data mahasiswa tidak valid',
            'tmp_lahir_mhs.required'            => 'Tempat Lahir Mahasiswa wajib diisi.',
            'tgl_lahir_mhs.required'            => 'Tanggal Lahir Mahasiswa wajib diisi.',
            'nama_ort.required'            => 'Nama Orang Tua / Wali wajib diisi.',
            'tmp_lahir_ort.required'            => 'Tempat Lahir Orang Tua / Wali wajib diisi.',
            'tgl_lahir_ort.required'            => 'Tanggal Lahir Orang Tua / Wali wajib diisi.',
            'nip_ort.required'            => 'NIP Orang Tua / Wali wajib diisi.',
            'pango_ort.required'            => 'Pangkat / Golongan Orang Tua / Wali wajib diisi.',
            'jabatan_ort.required'            => 'Jabatan Orang Tua / Wali wajib diisi.',
            'instansi_ort.required'            => 'Instasi Orang Tua / Wali wajib diisi.',
        ]);

        $dosen = json_decode($request->dosen_data);
        $mahasiswa = json_decode($request->mahasiswa_data);

        DB::table('surat_aktif_kuliah')->insert([  
            'nama' => $dosen->nama,
            'nip' => $dosen->nip,
            'pango' => $dosen->pangol,
            'jabatan' => $dosen->jf,
            'nama_mhs' => $mahasiswa->nama,
            'tmp_lahir_mhs' => $request->tmp_lahir_mhs,
            'tgl_lahir_mhs' => $request->tgl_lahir_mhs,
            'no_bp' => $mahasiswa->nobp,
            'semester' => $mahasiswa->ukt,
            'tahun_akademik' => $mahasiswa->tahun_ajaran,
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
        $mahasiswa = DB::table('mahasiswa')
        ->join('tahun_ajaran', 'mahasiswa.id_tahun_ajaran', '=', 'tahun_ajaran.id')
        ->select(
            'mahasiswa.*',
            'tahun_ajaran.nama as tahun_ajaran'
        )
        ->orderBy('mahasiswa.id', 'DESC')
        ->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $surataktifkuliah = DB::table('surat_aktif_kuliah')->where('id',$id)->first();
        
        return view('admin.surataktifkuliah.edit',['surataktifkuliah'=>$surataktifkuliah,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        // Validasi
        $request->validate([
            'dosen_data' => 'required|exists:dosen_data',
            'mahasiswa_data' => 'required|exists:mahasiswa_data',
            'tmp_lahir_mhs' => 'required|string|max:255',
            'tgl_lahir_mhs' => 'required|string|max:255',
            'nama_ort' => 'required|string|max:255',
            'tmp_lahir_ort' => 'required|string|max:255',
            'tgl_lahir_ort' => 'required|string|max:255',
            'nip_ort' => 'required|string|max:255',
            'pango_ort' => 'required|string|max:255',
            'jabatan_ort' => 'required|string|max:255',
            'instansi_ort' => 'required|string|max:255',
        ],[
            'dosen_data.required'            => 'Silahkan Pilih dulu data dosen terlebih dahulu.',
            'dosen_data.exists'            => 'Pilihan dulu data dosen tidak valid.',
            'mahasiswa_data.required'            => 'Silahkan Pilih dulu data mahasiswa terlebih dahulu.',
            'mahasiswa_data.exists'            => 'Pilihan dulu data mahasiswa tidak valid',
            'tmp_lahir_mhs.required'            => 'Tempat Lahir Mahasiswa wajib diisi.',
            'tgl_lahir_mhs.required'            => 'Tanggal Lahir Mahasiswa wajib diisi.',
            'nama_ort.required'            => 'Nama Orang Tua / Wali wajib diisi.',
            'tmp_lahir_ort.required'            => 'Tempat Lahir Orang Tua / Wali wajib diisi.',
            'tgl_lahir_ort.required'            => 'Tanggal Lahir Orang Tua / Wali wajib diisi.',
            'nip_ort.required'            => 'NIP Orang Tua / Wali wajib diisi.',
            'pango_ort.required'            => 'Pangkat / Golongan Orang Tua / Wali wajib diisi.',
            'jabatan_ort.required'            => 'Jabatan Orang Tua / Wali wajib diisi.',
            'instansi_ort.required'            => 'Instasi Orang Tua / Wali wajib diisi.',
        ]);

        $dosen = json_decode($request->dosen_data);
        $mahasiswa = json_decode($request->mahasiswa_data);
        DB::table('surat_aktif_kuliah')  
            ->where('id', $id)
            ->update([
            'nama' => $dosen->nama,
            'nip' => $dosen->nip,
            'pango' => $dosen->pangol,
            'jabatan' => $dosen->jf,
            'nama_mhs' => $mahasiswa->nama,
            'tmp_lahir_mhs' => $request->tmp_lahir_mhs,
            'tgl_lahir_mhs' => $request->tgl_lahir_mhs,
            'no_bp' => $mahasiswa->nobp,
            'semester' => $mahasiswa->ukt,
            'tahun_akademik' => $mahasiswa->tahun_ajaran,
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
