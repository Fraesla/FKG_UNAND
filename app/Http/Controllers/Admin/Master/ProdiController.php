<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ProdiController extends Controller
{
    public function read(Request $request){
        // $prodi = DB::table('prodi')->orderBy('id','DESC')->get();
        $entries = $request->input('entries', 5);

        $prodi = DB::table('prodi')
        ->join('jurusan', 'prodi.id_jurusan', '=', 'jurusan.id')
        ->select('prodi.*', 'jurusan.nama as nama_jurusan')
        ->orderBy('prodi.id', 'DESC')
        ->paginate($entries);

        $prodi->appends($request->all());

        return view('admin.master.prodi.index',['prodi'=>$prodi]);

    }

    public function feature(Request $request)
    {
        $query = DB::table('prodi')
        ->join('jurusan', 'prodi.id_jurusan', '=', 'jurusan.id')
        ->select('prodi.*', 'jurusan.nama as nama_jurusan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('prodi.id', 'like', "%{$search}%")
                  ->orWhere('prodi.nama', 'like', "%{$search}%")
                  ->orWhere('jurusan.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $prodi = $query->orderBy('prodi.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $prodi->appends($request->all());

        return view('admin.master.prodi.index', compact('prodi'));
    }

    public function add(){
        $jurusan = DB::table('jurusan')->orderBy('id','DESC')->get();
        return view('admin.master.prodi.create',['jurusan'=>$jurusan]);
    }

    public function create(Request $request){
        DB::table('prodi')->insert([  
            'id_jurusan' => $request->id_jurusan,
            'nama' => $request->nama]);

        return redirect('/admin/prodi')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $prodi = DB::table('prodi')->where('id',$id)->first();
        $jurusan = DB::table('jurusan')->orderBy('id','DESC')->get();
        
        return view('admin.master.prodi.edit',['prodi'=>$prodi, 'jurusan'=>$jurusan]);
    }

    public function update(Request $request, $id) {
        DB::table('prodi')  
            ->where('id', $id)
            ->update([
            'id_jurusan' => $request->id_jurusan,
            'nama' => $request->nama]);

        return redirect('/admin/prodi')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('prodi')->where('id',$id)->delete();

        return redirect('/admin/prodi')->with("success","Data Berhasil Dihapus !");
    }
}
