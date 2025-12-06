<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class BlokMahasiswaController extends Controller
{
   public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query join ke tabel kelas, tahun_ajaran, dan mahasiswa
        $blok = DB::table('blok_mahasiswa')
            ->join('mahasiswa', 'blok_mahasiswa.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('kelas', 'blok_mahasiswa.id_blok', '=', 'kelas.id')
            ->join('tahun_ajaran', 'blok_mahasiswa.id_tahun_ajaran', '=', 'tahun_ajaran.id')
            ->select(
                'blok_mahasiswa.*',
                'mahasiswa.nobp',
                'mahasiswa.nama as mahasiswa',
                'kelas.nama as blok',
                'tahun_ajaran.nama as tahun_ajar',
                'tahun_ajaran.semester as semester'
            )
            ->orderBy('blok_mahasiswa.id', 'DESC')
            ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $blok->appends($request->all());

        $username = auth()->user()->username;
        $mahasiswa = DB::table('mahasiswa')->where('nobp', $username)->first();

        return view('mahasiswa.blokmahasiswa.index', compact('blok','mahasiswa'));
    }

    public function feature(Request $request)
    {
        $query = DB::table('blok_mahasiswa')
        ->join('mahasiswa', 'blok_mahasiswa.id_mahasiswa', '=', 'mahasiswa.id')
        ->join('kelas', 'blok_mahasiswa.id_blok', '=', 'kelas.id')
        ->join('tahun_ajaran', 'blok_mahasiswa.id_tahun_ajaran', '=', 'tahun_ajaran.id')
        ->select(
            'blok_mahasiswa.*',
            'mahasiswa.nim',
            'mahasiswa.nama as mahasiswa',
            'kelas.nama as blok',
            'tahun_ajaran.nama as tahun_ajar',
            'tahun_ajaran.semester as semester'
        );

        // Search berdasarkan field blok_mahasiswa & mahasiswa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('blok_mahasiswa.id', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nim', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('tahun_ajaran.nama', 'like', "%{$search}%")
                  ->orWhere('tahun_ajaran.semester', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $blok = $query->orderBy('blok.id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $blok->appends($request->all());

        return view('mahasiswa.blokmahasiswa.index', compact('blok'));
    }

    public function add(){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $tahun_ajaran = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();
        return view('mahasiswa.blokmahasiswa.create',['mahasiswa'=>$mahasiswa,'blok'=>$blok,'tahun_ajaran'=>$tahun_ajaran]);
    }

    public function create(Request $request){

        DB::table('blok_mahasiswa')->insert([  
            'id_blok' => $request->id_blok,
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
            'id_mahasiswa' => $request->id_mahasiswa
        ]);

        return redirect('/mahasiswa/blokmahasiswa')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $blok = DB::table('blok_mahasiswa')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $kelas = DB::table('kelas')->orderBy('id','DESC')->get();
        $tahun_ajaran = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();
        
        return view('mahasiswa.blokmahasiswa.edit',['blok'=>$blok,'mahasiswa'=>$mahasiswa,'kelas'=>$kelas,'tahun_ajaran'=>$tahun_ajaran]);
    }

    public function update(Request $request, $id) {


        // ambil data lama
        $blok = DB::table('blok_mahasiswa')->where('id', $id)->first();

        $updateData = [
            'id_blok' => $request->id_blok,
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
            'id_mahasiswa' => $request->id_mahasiswa
        ];


        // update DB
        DB::table('blok_mahasiswa')->where('id', $id)->update($updateData);

        return redirect('/mahasiswa/blokmahasiswa')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('blok_mahasiswa')->where('id', $id)->delete();

        return redirect('/mahasiswa/blokmahasiswa')->with("success","Data Berhasil Dihapus !");
    }
}
