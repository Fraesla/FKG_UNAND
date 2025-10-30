<?php

namespace App\Http\Controllers\Dosen;

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
        ->join('makul', 'nilai.id_makul', '=', 'makul.id')
        ->join('dosen', 'nilai.id_dosen', '=', 'dosen.id')
        ->leftJoin('mahasiswa', 'nilai.id_mahasiswa', '=', 'mahasiswa.id')
        ->select(
            'nilai.*',
            'makul.nama as makul',
            'dosen.nama as dosen',
            DB::raw('COALESCE(mahasiswa.nama, "-") as mahasiswa'),
            'nilai.nilai'
        )
        ->orderBy('nilai.id', 'DESC')
        ->paginate($entries);

        $nilai->appends($request->all());

        return view('dosen.nilai.index', [
            'nilai' => $nilai
        ]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('nilai')
        ->join('makul', 'nilai.id_makul', '=', 'makul.id')
        ->join('dosen', 'nilai.id_dosen', '=', 'dosen.id')
        ->leftJoin('mahasiswa', 'nilai.id_mahasiswa', '=', 'mahasiswa.id')
        ->select(
            'nilai.*',
            'makul.nama as makul',
            'dosen.nama as dosen',
            DB::raw('COALESCE(mahasiswa.nama, "-") as mahasiswa'),
            'nilai.nilai'
        );

        // 🔎 Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('makul.nama', 'like', "%{$search}%")
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

        return view('dosen.nilai.index', compact('nilai'));
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
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('dosen.nilai.create',['makul'=>$makul,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function create(Request $request){
        $request->validate([
            'id_makul' => 'required|exists:makul,id',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'id_dosen' => 'required|exists:dosen,id',
            'nilai' => 'required|string|max:100',
        ],[
            'id_makul.exists' => ' Mata Kuliah yang dipilih tidak valid...',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'id_dosen.exists' => 'Dosen yang dipilih tidak valid..',
            'nilai.required' => 'Nilai wajib diisi.',
        ]);
        DB::table('nilai')->insert([  
            'id_makul' => $request->id_makul,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_dosen' => $request->id_dosen,
            'nilai' => $request->nilai]);

        return redirect('/dosen/nilai')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $makul = DB::table('makul')->orderBy('id','DESC')->get();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        $nilai = DB::table('nilai')->where('id',$id)->first();
        return view('dosen.nilai.edit',['nilai'=>$nilai,'makul'=>$makul,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'id_makul' => 'required|exists:makul,id',
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'id_dosen' => 'required|exists:dosen,id',
            'nilai' => 'required|string|max:100',
        ],[
            'id_makul.exists' => ' Mata Kuliah yang dipilih tidak valid...',
            'id_mahasiswa.exists' => 'Mahasiswa yang dipilih tidak valid..',
            'id_dosen.exists' => 'Dosen yang dipilih tidak valid..',
            'nilai.required' => 'Nilai wajib diisi.',
        ]);
        DB::table('nilai')  
            ->where('id', $id)
            ->update([
            'id_makul' => $request->id_makul,
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_dosen' => $request->id_dosen,
            'nilai' => $request->nilai]);

        return redirect('/dosen/nilai')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('nilai')->where('id',$id)->delete();

        return redirect('/dosen/nilai')->with("success","Data Berhasil Dihapus !");
    }
}
