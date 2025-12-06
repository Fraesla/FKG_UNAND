<?php

namespace App\Http\Controllers\admin\Akun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MahasiswaExport;
use App\Imports\MahasiswaImport;
use Auth;

class MahasiswaController extends Controller
{
    public function read(Request $request){
        
        $entries = $request->input('entries', 5);

        $mahasiswa = DB::table('mahasiswa as m')
            ->leftJoin('tahun_ajaran as ta', 'm.id_tahun_ajaran', '=', 'ta.id')
            ->select(
                'm.id',
                'm.nobp',
                'm.nama',
                'm.gender',
                'm.contact',
                'm.alamat',
                'ta.nama as tahun_ajaran',
                'ta.ukt as ukt',
                'ta.semester as semester',
                'ta.status as status',
                'm.foto as foto'
            )
            ->orderBy('m.id', 'DESC')
            ->paginate($entries);

         $mahasiswa->appends($request->all());
         $username = auth()->user()->username;

        return view('admin.akun.mahasiswa.index',['mahasiswa'=>$mahasiswa,'username'=>$username]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('mahasiswa as m')
            ->join('tahun_ajaran as ta', 'm.id_tahun_ajaran', '=', 'ta.id')
            ->select(
                'm.id',
                'm.nobp',
                'm.nama',
                'm.gender',
                'm.contact',
                'm.alamat',
                'ta.nama as tahun_ajaran',
                'ta.ukt as ukt',
                'ta.semester as semester',
                'ta.status as status',
                'm.foto as foto'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('m.id', 'like', "%{$search}%")
                  ->orWhere('m.nobp', 'like', "%{$search}%")
                  ->orWhere('m.nama', 'like', "%{$search}%")
                  ->orWhere('m.gender', 'like', "%{$search}%")
                  ->orWhere('m.contact', 'like', "%{$search}%")
                  ->orWhere('m.alamat', 'like', "%{$search}%")
                  ->orWhere('ta.nama', 'like', "%{$search}%")
                  ->orWhere('ta.ukt', 'like', "%{$search}%")
                  ->orWhere('ta.semester', 'like', "%{$search}%")
                  ->orWhere('ta.status', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $mahasiswa = $query->orderBy('ad.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $mahasiswa->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.akun.mahasiswa.index', compact('mahasiswa','username'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new MahasiswaImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Mahasiswa berhasil diimport!');
    }

    public function add(){
        $tahun = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();
        return view('admin.akun.mahasiswa.create',['tahun'=>$tahun]);
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'nobp' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'id_tahun_ajaran' => 'nullable|exists:tahun_ajaran,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ],[
            'nobp.required' => 'NO.BP wajib diisi.',
            'nama.required' => 'Nama Lengkap Mahaiswa wajib diisi.',
            'gender.required' => 'Jenis Kelamin wajib diisi.',
        ]);

        // Simpan file ke storage/public/foto_mahasiswa
        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_mahasiswa', 'public');
        }

        // Simpan ke database
        DB::table('mahasiswa')->insert([  
            'nobp' => $request->nobp,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'contact' => $request->contact,
            'alamat' => $request->alamat,
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
            'foto' => $path, // bisa null jika tidak diunggah
        ]);

        DB::table('user')->insert([  
            'username' => $request->nobp,
            'password' => bcrypt('Unand2025'),
            'level' => 'mahasiswa',
            'status' => '0',
        ]);

        return redirect('/admin/mahasiswa')->with("success","Data Berhasil Ditambah !");
    } 

    public function edit($id){
        $mahasiswa = DB::table('mahasiswa')->where('id',$id)->first();
        $tahun = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();
        
        return view('admin.akun.mahasiswa.edit',['mahasiswa'=>$mahasiswa,'tahun'=>$tahun]);
    }

    public function update(Request $request, $id) {
        
         $mahasiswa = DB::table('mahasiswa')->where('id',$id)->first();

        // Validasi input
        $request->validate([
            'nobp' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ],[
            'nobp.required' => 'NO.BP wajib diisi.',
            'nama.required' => 'Nama Lengkap Mahaiswa wajib diisi.',
            'gender.required' => 'Jenis Kelamin wajib diisi.',
        ]);

        $dataUpdate = [
            'nobp' => $request->nobp,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'contact' => $request->contact,
            'alamat' => $request->alamat,
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
        ];

        // kalau ada upload foto baru
        $path = null;
        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto && file_exists(storage_path('app/public/'.$mahasiswa->foto))) {
                unlink(storage_path('app/public/'.$mahasiswa->foto));
            }

            $path = $request->file('foto')->store('foto_mahasiswa', 'public');
            $dataUpdate['foto'] = $path;
        }

        DB::table('mahasiswa')
            ->where('id', $id)
            ->update($dataUpdate);

        DB::table('user')
            ->where('username', $mahasiswa->nobp)     // cari berdasarkan No.BP lama
            ->update([
                'username' => $request->nobp       // update ke No.BP baru
            ]);

        return redirect('/admin/mahasiswa')->with("success","Data Berhasil Diupdate !");
    }
    public function delete($id)
    {
        // Ambil data mahasiswa dulu (butuh nobp)
        $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();

        if (!$mahasiswa) {
            return redirect('/admin/mahasiswa')->with("error", "Data mahasiswa tidak ditemukan!");
        }

        // ================================
        // 🔥 HAPUS USER BERDASARKAN USERNAME = No.BP
        // ================================
        DB::table('user')
            ->where('username', $mahasiswa->nobp)
            ->delete();

        // ================================
        // 🔥 HAPUS DATA MAHASISWA
        // ================================
        DB::table('mahasiswa')->where('id', $id)->delete();

        return redirect('/admin/mahasiswa')->with("success", "Data mahasiswa & akun user berhasil dihapus!");
    }
}
