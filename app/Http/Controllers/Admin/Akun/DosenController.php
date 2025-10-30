<?php

namespace App\Http\Controllers\admin\Akun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DosenExport;
use App\Imports\DosenImport;
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
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%")
                  ->orWhere('gender', 'like', "%{$search}%")
                  ->orWhere('pangol', 'like', "%{$search}%")
                  ->orWhere('napater', 'like', "%{$search}%")
                  ->orWhere('napaber', 'like', "%{$search}%")
                  ->orWhere('jf', 'like', "%{$search}%")
                  ->orWhere('js', 'like', "%{$search}%")
                  ->orWhere('najater', 'like', "%{$search}%")
                  ->orWhere('penter', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $dosen = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $dosen->appends($request->all());

        return view('admin.akun.dosen.index', compact('dosen'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new DosenImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Dosen berhasil diimport!');
    }

    public function add(){
        return view('admin.akun.dosen.create');
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'nama' => 'required |string|max:255',
            'nip' => 'required|string|max:255',
            'nidn' => 'nullable|string|max:255',
            'gender' => 'required|string|max:255',
            'pangol' => 'nullable|string|max:255',
            'napater' => 'nullable|string|max:255',
            'napaber' => 'nullable|string|max:255',
            'jf' => 'nullable|string|max:255',
            'js' => 'nullable|string|max:255',
            'najater' => 'nullable|string|max:255',
            'penter' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ],[
            'nama.required' => 'Nama Dosen wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'gender.required' => 'Jenis Kelamin wajib diisi.',
        ]);

        // Simpan file ke storage/public/foto_dosen
        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('foto_dosen', 'public');
        }

        // Simpan ke database
        DB::table('dosen')->insert([  
            'nama' => $request->nama,
            'nip' => $request->nip,
            'nidn' => $request->nidn,
            'gender' => $request->gender,
            'pangol' => $request->pangol,
            'napater' => $request->napater,
            'napaber' => $request->napaber,
            'jf' => $request->jf,
            'js' => $request->js,
            'najater' => $request->najater,
            'penter' => $request->penter,
            'keterangan' => $request->keterangan,
            'foto' => $path
        ],[
            'nama.required' => 'Nama Dosen wajib diisi.',
            'nip.required' => 'NIP wajib diisi.',
            'gender.required' => 'Jenis Kelamin wajib diisi.',
        ]);

        DB::table('user')->insert([  
            'username' => $request->nip,
            'password' => bcrypt('Unand2025'),
            'level' => 'dosen',
            'status' => '0',
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
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'nidn' => 'nullable|string|max:255',
            'gender' => 'required|string|max:255',
            'pangol' => 'nullable|string|max:255',
            'napater' => 'nullable|string|max:255',
            'napaber' => 'nullable|string|max:255',
            'jf' => 'nullable|string|max:255',
            'js' => 'nullable|string|max:255',
            'najater' => 'nullable|string|max:255',
            'penter' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $dataUpdate = [
            'nama' => $request->nama,
            'nip' => $request->nip,
            'nidn' => $request->nidn,
            'gender' => $request->gender,
            'pangol' => $request->pangol,
            'napater' => $request->napater,
            'napaber' => $request->napaber,
            'jf' => $request->jf,
            'js' => $request->js,
            'najater' => $request->najater,
            'penter' => $request->penter,
            'keterangan' => $request->keterangan,
        ];

        // kalau ada upload foto baru
        $path = null;
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
