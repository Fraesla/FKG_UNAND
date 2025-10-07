<?php

namespace App\Http\Controllers\admin\jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JadMakulController extends Controller
{
    public function read(Request $request){

        $entries = $request->input('entries', 5);

        // Data blok (kelas) untuk select option
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();

        // Query jadwal_makul
        $query = DB::table('jadwal_makul')
            ->join('kelas', 'jadwal_makul.id_kelas', '=', 'kelas.id')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'kelas.nama as kelas',
                'jadwal_makul.minggu',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as makul',
                'ruangan.nama as ruangan'
            )
            ->orderBy('jadwal_makul.id', 'DESC');

        // Filter berdasarkan blok (id_kelas) kalau dipilih
        if ($request->filled('id_kelas')) {
            $query->where('jadwal_makul.id_kelas', $request->id_kelas);
        }

        // Pagination
        $jadmakul = $query->paginate($entries);
        $jadmakul->appends($request->all());

        return view('admin.jadwal.makul.index', [
            'jadmakul' => $jadmakul,
            'blok'     => $blok
        ]);
    } 

    public function feature(Request $request)
    {
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $query = DB::table('jadwal_makul')
            ->join('kelas', 'jadwal_makul.id_kelas', '=', 'kelas.id')
            ->join('makul', 'jadwal_makul.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_makul.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_makul.id',
                'kelas.nama as kelas',
                'jadwal_makul.minggu',
                'jadwal_makul.hari',
                'jadwal_makul.jam_mulai',
                'jadwal_makul.jam_selesai',
                'makul.nama as makul',
                'ruangan.nama as ruangan'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jadwal_makul.id', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.hari', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.jam_mulai', 'like', "%{$search}%")
                  ->orWhere('jadwal_makul.jam_selesai', 'like', "%{$search}%")
                  ->orWhere('makul.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $jadmakul = $query->orderBy('jadwal_makul.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jadmakul->appends($request->all());

        return view('admin.jadwal.makul.index', compact('jadmakul','blok'));
    }

    public function add(){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('admin.jadwal.makul.create',['blok'=>$blok,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function create(Request $request){
        DB::table('jadwal_makul')->insert([  
            'id_kelas' => $request->id_kelas,
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jadmakul = DB::table('jadwal_makul')->where('id',$id)->first();
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        
        return view('admin.jadwal.makul.edit',['jadmakul'=>$jadmakul,'blok'=>$blok,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        DB::table('jadwal_makul')  
            ->where('id', $id)
            ->update([
            'id_kelas' => $request->id_kelas,
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jadwal_makul')->where('id',$id)->delete();

        return redirect('/admin/jadmakul')->with("success","Data Berhasil Dihapus !");
    }
}
