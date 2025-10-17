<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KelasExport;
use App\Imports\KelasImport;
use Auth;

class KelasController extends Controller
{
    public function read(Request $request){
        // $kelas = DB::table('kelas')->orderBy('id','DESC')->get();
        $entries = $request->input('entries', 5);

        $kelas = DB::table('kelas')
        ->join('prodi', 'kelas.id_prodi', '=', 'prodi.id')
        ->select('kelas.*', 'prodi.nama as nama_prodi')
        ->orderBy('kelas.id', 'DESC')
        ->paginate($entries);

        $kelas->appends($request->all());

        return view('admin.master.kelas.index',['kelas'=>$kelas]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('kelas')
        ->join('prodi', 'kelas.id_prodi', '=', 'prodi.id')
        ->select('kelas.*', 'prodi.nama as nama_prodi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kelas.id', 'like', "%{$search}%")
                  ->orWhere('kelas.nama', 'like', "%{$search}%")
                  ->orWhere('prodi.nama', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data dengan pagination
        $kelas = $query->orderBy('kelas.id', 'DESC')->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $kelas->appends($request->all());

        return view('admin.master.kelas.index', compact('kelas'));
    }

    public function export()
    {
        $fileName = 'data_blok_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new KelasExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new KelasImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data blok berhasil diimport!');
    }
    
    public function add(){
        $prodi = DB::table('prodi')->orderBy('id','DESC')->get();
        return view('admin.master.kelas.create',['prodi'=>$prodi]);
    }

    public function create(Request $request){
        $request->validate([
            'nama' => 'required|string|max:100',
            'id_prodi' => 'required|exists:prodi,id'
        ],[
            'nama.required' => 'Nama Blok wajib diisi.',
            'id_prodi.exists' => 'Prodi yang dipilih tidak valid..',
        ]);
        DB::table('kelas')->insert([  
            'id_prodi' => $request->id_prodi,
            'nama' => $request->nama]);

        return redirect('/admin/kelas')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $kelas = DB::table('kelas')->where('id',$id)->first();
        $prodi = DB::table('prodi')->orderBy('id','DESC')->get();
        
        return view('admin.master.kelas.edit',['kelas'=>$kelas, 'prodi'=>$prodi]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string|max:100',
            'id_prodi' => 'required|exists:prodi,id'
        ],[
            'nama.required' => 'Nama Blok wajib diisi.',
            'id_prodi.exists' => 'Prodi yang dipilih tidak valid..',
        ]);
        DB::table('kelas')  
            ->where('id', $id)
            ->update([
            'id_prodi' => $request->id_prodi,
            'nama' => $request->nama]);

        return redirect('/admin/kelas')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('kelas')->where('id',$id)->delete();

        return redirect('/admin/kelas')->with("success","Data Berhasil Dihapus !");
    }
}
