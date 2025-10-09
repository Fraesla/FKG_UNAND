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

        // Query dengan join mahasiswa
        $saps = DB::table('saps')
                    ->join('mahasiswa', 'saps.id_mahasiswa', '=', 'mahasiswa.id')
                    ->select(
                        'saps.*',
                        'mahasiswa.nobp',
                        'mahasiswa.nama'
                    )
                    ->orderBy('saps.id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $saps->appends($request->all());

        return view('admin.saps.index', compact('saps'));
    }

    public function feature(Request $request)
    {
        $query = DB::table('saps')
                    ->join('mahasiswa', 'saps.id_mahasiswa', '=', 'mahasiswa.id')
                    ->select(
                        'saps.*',
                        'mahasiswa.nobp',
                        'mahasiswa.nama'
                    );

        // Search berdasarkan field saps & mahasiswa
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('saps.id', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('saps.jml_point_a', 'like', "%{$search}%")
                  ->orWhere('saps.jml_point_b', 'like', "%{$search}%")
                  ->orWhere('saps.jml_point_c', 'like', "%{$search}%")
                  ->orWhere('saps.total', 'like', "%{$search}%")
                  ->orWhere('saps.predikat', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $saps = $query->orderBy('saps.id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $saps->appends($request->all());

        return view('admin.saps.index', compact('saps'));
    }

    public function add(){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        return view('admin.saps.create',['mahasiswa'=>$mahasiswa]);
    }

    public function create(Request $request){

        // Hitung total dari point A, B, C
        $total = (int) $request->jml_point_a + (int) $request->jml_point_b + (int) $request->jml_point_c;

        // Tentukan predikat berdasarkan total
        if ($total < 101) {
            $predikat = "Kurang Aktif";
        } elseif ($total < 201) {
            $predikat = "Cukup Aktif";
        } elseif ($total < 301) {
            $predikat = "Aktif";
        } else {
            $predikat = "Sangat Aktif";
        }

        DB::table('saps')->insert([  
            'id_mahasiswa' => $request->id_mahasiswa,
            'jml_point_a' => $request->jml_point_a,
            'jml_point_b' => $request->jml_point_b,
            'jml_point_c' => $request->jml_point_c,
            'total' => $total,
            'predikat' => $predikat
        ]);

        return redirect('/admin/saps')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $saps = DB::table('saps')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        return view('admin.saps.edit',['saps'=>$saps,'mahasiswa'=>$mahasiswa]);
    }

    public function update(Request $request, $id) {

        // Hitung total dari point A, B, C
        $total = (int) $request->jml_point_a + (int) $request->jml_point_b + (int) $request->jml_point_c;

        // Tentukan predikat berdasarkan total
        if ($total < 101) {
            $predikat = "Kurang Aktif";
        } elseif ($total < 201) {
            $predikat = "Cukup Aktif";
        } elseif ($total < 301) {
            $predikat = "Aktif";
        } else {
            $predikat = "Sangat Aktif";
        }

        DB::table('saps')  
            ->where('id', $id)
            ->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'jml_point_a' => $request->jml_point_a,
            'jml_point_b' => $request->jml_point_b,
            'jml_point_c' => $request->jml_point_c,
            'total' => $total,
            'predikat' => $predikat
        ]);

        return redirect('/admin/saps')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('saps')->where('id',$id)->delete();

        return redirect('/admin/saps')->with("success","Data Berhasil Dihapus !");
    }
}
