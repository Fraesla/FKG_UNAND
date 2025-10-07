<?php

namespace App\Http\Controllers\Admin\Jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class JadMetopenController extends Controller
{
    public function read(Request $request){

        $entries = $request->input('entries', 5);

        // Data blok (kelas) untuk select option
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();

        // Query jadwal_metopen
        $query = DB::table('jadwal_metopen')
            ->join('makul', 'jadwal_metopen.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_metopen.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_metopen.id',
                'jadwal_metopen.minggu',
                'jadwal_metopen.hari',
                'jadwal_metopen.jam_mulai',
                'jadwal_metopen.jam_selesai',
                'makul.nama as makul',
                'ruangan.nama as ruangan'
            )
            ->orderBy('jadwal_metopen.id', 'DESC');

        // Filter berdasarkan blok (id_kelas) kalau dipilih
        if ($request->filled('id_kelas')) {
            $query->where('jadwal_metopen.id_kelas', $request->id_kelas);
        }

        // Pagination
        $jadmetopen = $query->paginate($entries);
        $jadmetopen->appends($request->all());

        return view('admin.jadwal.metopen.index', [
            'jadmetopen' => $jadmetopen,
            'blok'     => $blok
        ]);
    } 

    public function feature(Request $request)
    {
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $query = DB::table('jadwal_metopen')
            ->join('makul', 'jadwal_metopen.id_makul', '=', 'makul.id')
            ->join('ruangan', 'jadwal_metopen.id_ruangan', '=', 'ruangan.id')
            ->select(
                'jadwal_metopen.id',
                'jadwal_metopen.minggu',
                'jadwal_metopen.hari',
                'jadwal_metopen.jam_mulai',
                'jadwal_metopen.jam_selesai',
                'makul.nama as makul',
                'ruangan.nama as ruangan'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('jadwal_metopen.id', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.hari', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.jam_mulai', 'like', "%{$search}%")
                  ->orWhere('jadwal_metopen.jam_selesai', 'like', "%{$search}%")
                  ->orWhere('makul.nama', 'like', "%{$search}%")
                  ->orWhere('ruangan.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $jadmetopen = $query->orderBy('jadwal_metopen.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $jadmetopen->appends($request->all());

        return view('admin.jadwal.metopen.index', compact('jadmetopen','blok'));
    }

    public function add(){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        return view('admin.jadwal.metopen.create',['blok'=>$blok,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function create(Request $request){
        DB::table('jadwal_metopen')->insert([  
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmetopen')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $jadmetopen = DB::table('jadwal_metopen')->where('id',$id)->first();
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $ruangan = DB::table('ruangan')->orderBy('id','DESC')->get();
        
        return view('admin.jadwal.metopen.edit',['jadmetopen'=>$jadmetopen,'blok'=>$blok,'makul'=>$makul,'ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        DB::table('jadwal_metopen')  
            ->where('id', $id)
            ->update([
            'minggu' => $request->minggu,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'id_makul' => $request->id_makul,
            'id_ruangan' => $request->id_ruangan
        ]);

        return redirect('/admin/jadmetopen')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('jadwal_metopen')->where('id',$id)->delete();

        return redirect('/admin/jadmetopen')->with("success","Data Berhasil Dihapus !");
    }
}
