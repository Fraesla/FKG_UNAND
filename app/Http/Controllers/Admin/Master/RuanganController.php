<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RuanganExport;
use App\Imports\RuanganImport;
use Auth;

class RuanganController extends Controller
{
    public function read(Request $request){
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $ruangan = DB::table('ruangan')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $ruangan->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.master.ruangan.index', ['ruangan' => $ruangan,'username'=>$username]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('ruangan');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $ruangan = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $ruangan->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.master.ruangan.index', compact('ruangan','username'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        Excel::import(new RuanganImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Ruangan berhasil diimport!');
    }

    public function add(){
        return view('admin.master.ruangan.create');
    }

    public function create(Request $request){
        $request->validate([
            'nama' => 'required|string|max:100',
        ],[
            'nama.required' => 'Nama Ruangan wajib diisi.',
        ]);
        DB::table('ruangan')->insert([  
            'nama' => $request->nama]);

        return redirect('/admin/ruangan')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $ruangan = DB::table('ruangan')->where('id',$id)->first();
        
        return view('admin.master.ruangan.edit',['ruangan'=>$ruangan]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string|max:100',
        ],[
            'nama.required' => 'Nama Ruangan wajib diisi.',
        ]);
        DB::table('ruangan')  
            ->where('id', $id)
            ->update([
            'nama' => $request->nama]);

        return redirect('/admin/ruangan')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('ruangan')->where('id',$id)->delete();

        return redirect('/admin/ruangan')->with("success","Data Berhasil Dihapus !");
    }
}
