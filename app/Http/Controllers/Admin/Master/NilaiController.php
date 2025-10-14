<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NilaiExport;
use App\Imports\NilaiImport;
use Auth;

class NilaiController extends Controller
{
    public function read(Request $request)
    {
        $entries = $request->input('entries', 5);

        $nilai = DB::table('nilai')
            ->join('kelas', 'nilai.id_kelas', '=', 'kelas.id')
            ->join('mahasiswa', 'nilai.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('dosen', 'nilai.id_dosen', '=', 'dosen.id')
            ->select(
                'nilai.*',
                'kelas.nama as blok',
                'mahasiswa.nama as mahasiswa',
                'dosen.nama as dosen',
                'nilai.nilai'
            )
            ->orderBy('mahasiswa.nama', 'ASC') // contoh paginate berdasarkan mahasiswa
            ->paginate($entries);

        $nilai->appends($request->all());

        return view('admin.master.nilai.index', [
            'nilai' => $nilai
        ]);
    } 

    public function feature(Request $request)
    {
        $query = DB::table('nilai')
        ->join('kelas', 'nilai.id_kelas', '=', 'kelas.id')
        ->join('mahasiswa', 'nilai.id_mahasiswa', '=', 'mahasiswa.id')
        ->join('dosen', 'nilai.id_dosen', '=', 'dosen.id')
        ->select(
            'nilai.*',
            'kelas.nama as blok',
            'mahasiswa.nama as mahasiswa',
            'dosen.nama as dosen'
        );

        // 🔎 Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('dosen.nama', 'like', "%{$search}%")
                  ->orWhere('nilai.nilai', 'like', "%{$search}%");
            });
        }

        // 🔢 Entries per page (default 10)
        $entries = $request->get('entries', 10);

        // 📑 Ambil data dengan pagination
        $nilai = $query->orderBy('nilai.id', 'DESC')->paginate($entries);

        // 🚀 Supaya pagination tetap bawa query string (search / entries)
        $nilai->appends($request->all());

        return view('admin.master.nilai.index', compact('nilai'));
    }

    public function export()
    {
        $fileName = 'data_nilai' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new NilaiExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new NilaiImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data nilai berhasil diimport!');
    } 
    
    public function add(){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.master.nilai.create',['blok'=>$blok,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function create(Request $request){
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'id_dosen' => 'required|exists:dosen,id',
            'nilai' => 'required|string|max:100',
        ],[
            'id_kelas.exists' => ' Blok yang dipilih tidak valid...',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'id_dosen.exists' => 'Dosen yang dipilih tidak valid..',
            'nilai.required' => 'Nilai wajib diisi.',
        ]);
        DB::table('nilai')->insert([  
            'id_kelas' => $request->id_kelas,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_dosen' => $request->id_dosen,
            'nilai' => $request->nilai]);

        return redirect('/admin/nilai')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $blok = DB::table('kelas')->orderBy('id','DESC')->get();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $nilai = DB::table('nilai')->where('id',$id)->first();
        return view('admin.master.nilai.edit',['nilai'=>$nilai,'blok'=>$blok,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'id_dosen' => 'required|exists:dosen,id',
            'nilai' => 'required|string|max:100',
        ],[
            'id_kelas.exists' => ' Blok yang dipilih tidak valid...',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'id_dosen.exists' => 'Dosen yang dipilih tidak valid..',
            'nilai.required' => 'Nilai wajib diisi.',
        ]);
        DB::table('nilai')  
            ->where('id', $id)
            ->update([
            'id_kelas' => $request->id_kelas,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_dosen' => $request->id_dosen,
            'nilai' => $request->nilai]);

        return redirect('/admin/nilai')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('nilai')->where('id',$id)->delete();

        return redirect('/admin/nilai')->with("success","Data Berhasil Dihapus !");
    }
}
