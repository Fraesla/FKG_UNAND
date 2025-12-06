<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TaController extends Controller
{
    public function read(Request $request, $id_prodi = null)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);
        $user = Auth::user();
        $idDosen = $user->id_dosen ?? $user->id;
        // Query pakai join ke mahasiswa
        $query = DB::table('ta')
            ->join('mahasiswa', 'ta.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('prodi', 'ta.id_prodi', '=', 'prodi.id')
            ->join('dosen', 'ta.dosen_bimbingan', '=', 'dosen.id')
            ->select(
                'ta.*',
                'prodi.nama as prodi',
                'mahasiswa.nobp',
                'mahasiswa.nama',
                'dosen.nama as dosen',
            )
            ->orderBy('ta.id', 'DESC');

        // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('ta.id_prodi', $id_prodi);
        }

        // Pagination
        $ta = $query->paginate($entries);
        $ta->appends($request->all());
        $username = auth()->user()->username;
        $dosen = DB::table('dosen')->where('nip', $username)->first();

        return view('dosen.ta.index', ['ta' => $ta, 'dosen' => $dosen, 'id_prodi' => $id_prodi]);
    }

    public function feature(Request $request, $id_prodi = null)
    {
        $user = Auth::user();
        $idDosen = $user->id_dosen ?? $user->id;
        $query = DB::table('ta')
        ->join('mahasiswa', 'ta.id_mahasiswa', '=', 'mahasiswa.id')
        ->join('prodi', 'ta.id_prodi', '=', 'prodi.id')
        ->join('dosen', 'ta.dosen_bimbingan', '=', 'dosen.id')
        ->select(
            'ta.*',
            'prodi.nama as prodi',
            'mahasiswa.nobp',
            'mahasiswa.nama',
            'dosen.nama as dosen',
        );

        // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('ta.id_prodi', $id_prodi);
        }

        // Search berdasarkan ID/Nama/No BP/Dosen dll
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ta.id', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%")
                  ->orWhere('prodi.nama', 'like', "%{$search}%")
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

        $username = auth()->user()->username;
        $dosen = DB::table('dosen')->where('nip', $username)->first();

        return view('dosen.ta.index', compact('ta','dosen','id_prodi'));
    }

    public function add($id_prodi = null){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('dosen.ta.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen,'id_prodi'=>$id_prodi]);
    }

    public function create(Request $request){
        $user = Auth::user();
        $idDosen = $user->id_dosen ?? $user->id;
         // Validasi input
        $request->validate([
            'id_mahasiswa' => 'required|exists:makul,id',
            'tgl_bimbingan' => 'required|date',
            'catatan' => 'nullable|string',
        ],[
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'tgl_bimbingan.required' => 'Tanggal Bimbingan wajib diisi.'
        ]);
        DB::table('ta')->insert([  
            'id_prodi' => $request->id_prodi,
            'id_mahasiswa' => $request->id_mahasiswa,
            'dosen_bimbingan' => $idDosen,
            'tgl_bimbingan' => $request->tgl_bimbingan,
            'catatan' => $request->catatan
        ]);

        return redirect('/dosen/ta/'.$request->id_prodi)->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $ta = DB::table('ta')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('dosen.ta.edit',['ta'=>$ta,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
         // Validasi input
        $request->validate([
            'id_mahasiswa' => 'required|exists:makul,id',
            'tgl_bimbingan' => 'required|date',
            'catatan' => 'nullable|string',
        ],[
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'tgl_bimbingan.required' => 'Tanggal Bimbingan wajib diisi.',
        ]);
        DB::table('ta')  
            ->where('id', $id)
            ->update([
            'id_mahasiswa' => $request->id_mahasiswa,
            'tgl_bimbingan' => $request->tgl_bimbingan,
            'catatan' => $request->catatan
        ]);

        return redirect('/dosen/ta/'.$request->id_prodi)->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        // Ambil data berdasarkan ID
        $ta = DB::table('ta')->where('id', $id)->first();

        if (!$ta) {
            return redirect()->back()->with('error', 'Data Bimbingan TA tidak ditemukan!');
        }

        // Hapus jadwal
        DB::table('ta')->where('id', $id)->delete();

        return redirect('/dosen/ta/'.$ta->id_prodi)->with("success","Data Berhasil Dihapus !");
    }
}
