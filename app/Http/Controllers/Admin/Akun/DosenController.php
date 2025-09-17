<?php

namespace App\Http\Controllers\admin\Akun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class DosenController extends Controller
{
   public function read(Request $request){
        $entries = $request->input('entries', 5);

        $dosen = DB::table('dosen')->orderBy('id','DESC')->paginate($entries);

        $dosen->appends($request->all());

        return view('admin.akun.dosen.index',['dosen'=>$dosen]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('dosen');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nidm', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('gender', 'like', "%{$search}%")
                  ->orWhere('tgl_lahir', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $dosen = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $dosen->appends($request->all());

        return view('admin.akun.dosen.index', compact('dosen'));
    }

    public function add(){
        return view('admin.akun.dosen.create');
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'nidm' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'tgl_lahir' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ]);

        // Simpan file ke storage/public/foto_dosen
        $path = $request->file('foto')->store('foto_dosen', 'public');

        // Simpan ke database
        DB::table('dosen')->insert([  
            'nidm' => $request->nidm,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'foto' => $path
        ]);

        return redirect('/admin/dosen')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $dosen = DB::table('dosen')->where('id',$id)->first();
        
        return view('admin.akun.dosen.edit',['dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        
         $dosen = DB::table('dosen')->where('id',$id)->first();

        // Validasi input
        $request->validate([
            'nidm' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'tgl_lahir' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $dataUpdate = [
            'nidm' => $request->nidm,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ];

        // kalau ada upload foto baru
        if ($request->hasFile('foto')) {
            if ($dosen->foto && file_exists(storage_path('app/public/'.$dosen->foto))) {
                unlink(storage_path('app/public/'.$dosen->foto));
            }

            $path = $request->file('foto')->store('foto_dosen', 'public');
            $dataUpdate['foto'] = $path;
        }

        DB::table('dosen')
            ->where('id', $id)
            ->update($dataUpdate);

        return redirect('/admin/dosen')->with("success","Data Berhasil Diupdate !");
    }
    public function delete($id)
    {
        DB::table('dosen')->where('id',$id)->delete();

        return redirect('/admin/dosen')->with("success","Data Berhasil Dihapus !");
    }
}
