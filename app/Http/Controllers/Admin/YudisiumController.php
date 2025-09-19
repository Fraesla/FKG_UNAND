<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class YudisiumController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $yudisium = DB::table('yudisium')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $yudisium->appends($request->all());

        return view('admin.yudisium.index', ['yudisium' => $yudisium]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('yudisium');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('no_bp', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('tgl_semi_proposal', 'like', "%{$search}%")
                  ->orWhere('tgl_semi_hasil', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $yudisium = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $yudisium->appends($request->all());

        return view('admin.yudisium.index', compact('yudisium'));
    }

    public function add(){
        return view('admin.yudisium.create');
    }

    public function create(Request $request){
        DB::table('yudisium')->insert([  
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'judul' => $request->judul,
            'tgl_semi_proposal' => $request->tgl_semi_proposal,
            'tgl_semi_hasil' => $request->tgl_semi_hasil,
            'hasil_turnitin' => $request->hasil_turnitin,
            'bukti_lunas' => $request->bukti_lunas,
            'khs' => $request->khs,
            'kbs' => $request->kbs,
            'brsempro' => $request->brsempro,
            'brsemhas' => $request->brsemhas,
            'full_skripsi' => $request->full_skripsi,
            'matriks' => $request->matriks,
            'toefl' => $request->toefl
        ]);

        return redirect('/admin/yudisium')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $yudisium = DB::table('yudisium')->where('id',$id)->first();
        
        return view('admin.yudisium.edit',['yudisium'=>$yudisium]);
    }

    public function update(Request $request, $id) {
        DB::table('yudisium')  
            ->where('id', $id)
            ->update([
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'judul' => $request->judul,
            'tgl_semi_proposal' => $request->tgl_semi_proposal,
            'tgl_semi_hasil' => $request->tgl_semi_hasil,
            'hasil_turnitin' => $request->hasil_turnitin,
            'bukti_lunas' => $request->bukti_lunas,
            'khs' => $request->khs,
            'kbs' => $request->kbs,
            'brsempro' => $request->brsempro,
            'brsemhas' => $request->brsemhas,
            'full_skripsi' => $request->full_skripsi,
            'matriks' => $request->matriks,
            'toefl' => $request->toefl
        ]);

        return redirect('/admin/yudisium')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('yudisium')->where('id',$id)->delete();

        return redirect('/admin/yudisium')->with("success","Data Berhasil Dihapus !");
    }
}
