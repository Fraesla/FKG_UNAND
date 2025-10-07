<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JurusanController extends Controller
{
    public function read(Request $request){

        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $jurusan = DB::table('jurusan')
        ->join('fakultas', 'jurusan.id_fakultas', '=', 'fakultas.id')
        ->select('jurusan.*', 'fakultas.nama as nama_fakultas')
        ->orderBy('jurusan.id', 'DESC')
        ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jurusan->appends($request->all());


        return view('admin.master.jurusan.index',['jurusan'=>$jurusan]);
    }
    public function feature(Request $request)
    {
        $query = DB::table('jurusan')
        ->join('fakultas', 'jurusan.id_fakultas', '=', 'fakultas.id')
        ->select('jurusan.*', 'fakultas.nama as nama_fakultas');

        // Fitur Search (multi field: id jurusan, nama jurusan, nama fakultas)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jurusan.id', 'like', "%{$search}%")
                  ->orWhere('jurusan.nama', 'like', "%{$search}%")
                  ->orWhere('fakultas.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $jurusan = $query->orderBy('jurusan.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jurusan->appends($request->all());

        return view('admin.master.jurusan.index', compact('jurusan'));
    }
    public function add(){
        $fakultas = DB::table('fakultas')->orderBy('id','DESC')->get();
        return view('admin.master.jurusan.create',['fakultas'=>$fakultas]);
    }

    public function create(Request $request){
        $request->validate([
            'id_fakultas' => 'required|exists:fakultas,id',
            'nama' => 'required|string|max:100',
        ],[
            'id_fakultas.exists'   => 'Fakultas yang dipilih tidak valid.',
            'nama.required' => 'Nama Jurusan wajib diisi.',
        ]);

        DB::table('jurusan')->insert([  
            'id_fakultas' => $request->id_fakultas,
            'nama' => $request->nama]);

        return redirect('/admin/jurusan')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jurusan = DB::table('jurusan')->where('id',$id)->first();
        $fakultas = DB::table('fakultas')->orderBy('id','DESC')->get();
        
        return view('admin.master.jurusan.edit',['jurusan'=>$jurusan, 'fakultas'=>$fakultas]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'id_fakultas' => 'required|exists:fakultas,id',
            'nama' => 'required|string|max:100',
        ],[
            'id_fakultas.exists'   => 'Fakultas yang dipilih tidak valid.',
            'nama.required' => 'Nama Jurusan wajib diisi.',
        ]);
        DB::table('jurusan')  
            ->where('id', $id)
            ->update([
            'id_fakultas' => $request->id_fakultas,
            'nama' => $request->nama]);

        return redirect('/admin/jurusan')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jurusan')->where('id',$id)->delete();

        return redirect('/admin/jurusan')->with("success","Data Berhasil Dihapus !");
    }
}
