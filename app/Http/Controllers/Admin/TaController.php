<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TaController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai join ke mahasiswa
        $ta = DB::table('ta')
            ->join('mahasiswa', 'ta.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('dosen', 'ta.dosen_bimbingan', '=', 'dosen.id')
            ->select(
                'ta.*',
                'mahasiswa.nobp',
                'mahasiswa.nama',
                'dosen.nama as dosen',
            )
            ->orderBy('ta.id', 'DESC')
            ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $ta->appends($request->all());

        return view('admin.ta.index', ['ta' => $ta]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('ta')
        ->join('mahasiswa', 'ta.id_mahasiswa', '=', 'mahasiswa.id')
        ->join('dosen', 'ta.dosen_bimbingan', '=', 'dosen.id')
        ->select(
            'ta.*',
            'mahasiswa.nobp',
            'mahasiswa.nama',
            'dosen.nama as dosen',
        );

        // Search berdasarkan ID/Nama/No BP/Dosen dll
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ta.id', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('dosen.nama', 'like', "%{$search}%")
                  ->orWhere('ta.tgl_bimbingan', 'like', "%{$search}%")
                  ->orWhere('ta.catatan', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $ta = $query->orderBy('ta.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string
        $ta->appends($request->all());

        return view('admin.ta.index', compact('ta'));
    }

    public function add(){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.ta.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function create(Request $request){
         // Validasi input
        $request->validate([
            'id_mahasiswa' => 'required|exists:makul,id',
            'dosen_bimbingan' => 'required|exists:dosen,id',
            'tgl_bimbingan' => 'required|date',
            'catatan' => 'required|string',
        ],[
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'dosen_bimbingan.exists' => 'Dosen Bimbingan yang dipilih tidak valid..',
            'tgl_bimbingan.required' => 'Tanggal Bimbingan wajib diisi.',
            'catatan.required' => 'Catatan wajib diisi.',
        ]);
        DB::table('ta')->insert([  
            'id_mahasiswa' => $request->id_mahasiswa,
            'dosen_bimbingan' => $request->dosen_bimbingan,
            'tgl_bimbingan' => $request->tgl_bimbingan,
            'catatan' => $request->catatan
        ]);

        return redirect('/admin/ta')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $ta = DB::table('ta')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.ta.edit',['ta'=>$ta,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
         // Validasi input
        $request->validate([
            'id_mahasiswa' => 'required|exists:makul,id',
            'dosen_bimbingan' => 'required|exists:dosen,id',
            'tgl_bimbingan' => 'required|date',
            'catatan' => 'required|string',
        ],[
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'dosen_bimbingan.exists' => 'Dosen Bimbingan yang dipilih tidak valid..',
            'tgl_bimbingan.required' => 'Tanggal Bimbingan wajib diisi.',
            'catatan.required' => 'Catatan wajib diisi.',
        ]);
        DB::table('ta')  
            ->where('id', $id)
            ->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'dosen_bimbingan' => $request->dosen_bimbingan,
            'tgl_bimbingan' => $request->tgl_bimbingan,
            'catatan' => $request->catatan
        ]);

        return redirect('/admin/ta')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('ta')->where('id',$id)->delete();

        return redirect('/admin/ta')->with("success","Data Berhasil Dihapus !");
    }
}
