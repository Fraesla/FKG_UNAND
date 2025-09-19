<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SAPSController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $saps = DB::table('saps')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $saps->appends($request->all());

        return view('admin.saps.index', ['saps' => $saps]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('saps');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('jml_point_a', 'like', "%{$search}%")
                  ->orWhere('jml_point_b', 'like', "%{$search}%")
                  ->orWhere('jml_point_c', 'like', "%{$search}%")
                  ->orWhere('total', 'like', "%{$search}%")
                  ->orWhere('predikat', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $saps = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $saps->appends($request->all());

        return view('admin.saps.index', compact('saps'));
    }

    public function add(){
        return view('admin.saps.create');
    }

    public function create(Request $request){
        DB::table('saps')->insert([  
            'nim' => $request->nama,
            'nama' => $request->nama,
            'jml_point_a' => $request->jml_point_a,
            'jml_point_b' => $request->jml_point_b,
            'jml_point_c' => $request->jml_point_c,
            'total' => $request->total,
            'predikat' => $request->predikat
        ]);

        return redirect('/admin/saps')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $saps = DB::table('saps')->where('id',$id)->first();
        
        return view('admin.saps.edit',['saps'=>$saps]);
    }

    public function update(Request $request, $id) {
        DB::table('saps')  
            ->where('id', $id)
            ->update([
            'nim' => $request->nama,
            'nama' => $request->nama,
            'jml_point_a' => $request->jml_point_a,
            'jml_point_b' => $request->jml_point_b,
            'jml_point_c' => $request->jml_point_c,
            'total' => $request->total,
            'predikat' => $request->predikat
        ]);

        return redirect('/admin/saps')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('saps')->where('id',$id)->delete();

        return redirect('/admin/saps')->with("success","Data Berhasil Dihapus !");
    }
}
