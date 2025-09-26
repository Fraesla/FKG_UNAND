<?php

namespace App\Http\Controllers\admin\Akun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MahasiswaController extends Controller
{
    public function read(Request $request){
        $entries = $request->input('entries', 5);

        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->paginate($entries);

         $mahasiswa->appends($request->all());

        return view('admin.akun.mahasiswa.index',['mahasiswa'=>$mahasiswa]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('mahasiswa');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('gender', 'like', "%{$search}%")
                  ->orWhere('tgl_lahir', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('gmail', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $mahasiswa = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $mahasiswa->appends($request->all());

        return view('admin.akun.mahasiswa.index', compact('mahasiswa'));
    }

    public function add(){
        return view('admin.akun.mahasiswa.create');
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'gmail' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ]);

        // Simpan file ke storage/public/foto_dosen
        $path = $request->file('foto')->store('foto_mahasiswa', 'public');

        // Simpan ke database
        DB::table('mahasiswa')->insert([  
            'nim' => $request->nim,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'gmail' => $request->gmail,
            'foto' => $path
        ]);

        DB::table('user')->insert([  
            'username' => $request->nim,
            'password' => bcrypt('Unand2025'),
            'level' => 'mahasiswa',
            'status' => '0',
        ]);

        return redirect('/admin/mahasiswa')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $mahasiswa = DB::table('mahasiswa')->where('id',$id)->first();
        
        return view('admin.akun.mahasiswa.edit',['mahasiswa'=>$mahasiswa]);
    }

    public function update(Request $request, $id) {
        
         $mahasiswa = DB::table('mahasiswa')->where('id',$id)->first();

        // Validasi input
        $request->validate([
            'nim' => 'required|string|max:255',
            'nama' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:255',
            'gmail' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $dataUpdate = [
            'nim' => $request->nim,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'gmail' => $request->gmail,
        ];

        // kalau ada upload foto baru
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

        return redirect('/admin/mahasiswa')->with("success","Data Berhasil Diupdate !");
    }
    public function delete($id)
    {
        DB::table('mahasiswa')->where('id',$id)->delete();

        return redirect('/admin/mahasiswa')->with("success","Data Berhasil Dihapus !");
    }
}
