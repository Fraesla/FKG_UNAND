<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FakultasExport;
use App\Imports\FakultasImport;

class FakultasController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function read(Request $request){
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $fakultas = DB::table('fakultas')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $fakultas->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.master.fakultas.index', ['fakultas' => $fakultas, 'username' => $username]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('fakultas');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $fakultas = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $fakultas->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.master.fakultas.index', compact('fakultas','username'));
    }

    public function export()
    {
        $fileName = 'data_fakultas_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new FakultasExport, $fileName);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new FakultasImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data fakultas berhasil diimport!');
    }

    public function add(){
        return view('admin.master.fakultas.create');
    }

    public function create(Request $request){
        $request->validate([
            'nama' => 'required|string|max:100',
        ],[
            'nama.required' => 'Nama fakultas wajib diisi.',
        ]);

        DB::table('fakultas')->insert([  
            'nama' => $request->nama]);

        return redirect('/admin/fakultas')->with("success","Data Berhasil Ditambah !");
    } 

    public function edit($id){

        $fakultas = DB::table('fakultas')->where('id',$id)->first();
        
        return view('admin.master.fakultas.edit',['fakultas'=>$fakultas]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string|max:100',
        ],[
            'nama.required' => 'Nama fakultas wajib diisi.',
        ]);

        DB::table('fakultas')  
            ->where('id', $id)
            ->update([
            'nama' => $request->nama]);

        return redirect('/admin/fakultas')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('fakultas')->where('id',$id)->delete();

        return redirect('/admin/fakultas')->with("success","Data Berhasil Dihapus !");
    }
}
