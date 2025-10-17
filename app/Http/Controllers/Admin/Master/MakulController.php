<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MakulExport;
use App\Imports\MakulImport;
use Auth;

class MakulController extends Controller
{
    public function read(Request $request){

         $entries = $request->input('entries', 5);

        // Query pakai paginate
        $makul = DB::table('makul')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $makul->appends($request->all());

        return view('admin.master.makul.index',['makul'=>$makul]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('makul');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $makul = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $makul->appends($request->all());

        return view('admin.master.makul.index', compact('makul'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new MakulImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Makul berhasil diimport!');
    }

    public function add(){
        return view('admin.master.makul.create');
    }

    public function create(Request $request){
        $request->validate([
            'kode' => 'required|string|max:100',
            'nama' => 'required|string|max:100',
        ],[
            'kode.required' => 'Kode Mata Kuliah wajib diisi.',
            'nama.required' => 'Nama Mata Kuliah wajib diisi.',
        ]);
        DB::table('makul')->insert([  
            'kode' => $request->kode,'nama' => $request->nama]);

        return redirect('/admin/makul')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){

        $makul = DB::table('makul')->where('id',$id)->first();
        
        return view('admin.master.makul.edit',['makul'=>$makul]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'kode' => 'required|string|max:100',
            'nama' => 'required|string|max:100',
        ],[
            'kode.required' => 'Kode Mata Kuliah wajib diisi.',
            'nama.required' => 'Nama Mata Kuliah wajib diisi.',
        ]);
        DB::table('makul')  
            ->where('id', $id)
            ->update([
            'kode' => $request->kode,'nama' => $request->nama]);

        return redirect('/admin/makul')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('makul')->where('id',$id)->delete();

        return redirect('/admin/makul')->with("success","Data Berhasil Dihapus !");
    }
}
