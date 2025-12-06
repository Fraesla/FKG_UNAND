<?php

namespace App\Http\Controllers\admin\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TahunAjaranController extends Controller
{
    public function read(Request $request){
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $tahunajar = DB::table('tahun_ajaran')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $tahunajar->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.master.tahunajar.index', ['tahunajar' => $tahunajar,'username'=>$username]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('tahun_ajaran');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('ukt', 'like', "%{$search}%")
                  ->orWhere('semester', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $tahunajar = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $tahunajar->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.master.tahunajar.index', compact('tahunajar','username'));
    }

    public function add(){
        return view('admin.master.tahunajar.create');
    }

    public function create(Request $request){
       $request->validate([
            'nama' => 'required|string|max:100',
            'ukt' => 'required|string|max:100',
            'semester' => 'required|string|max:100',
            'status' => 'required|string|max:100',
        ],[
            'nama.required' => 'Nama Tahun Ajaran wajib diisi.',
            'ukt.required' => 'Level UKT wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ]);
        DB::table('tahun_ajaran')->insert([  
            'nama' => $request->nama,'ukt'=>$request->ukt,'semester'=>$request->semester,'status' => $request->status]);

        return redirect('/admin/tahunajar')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
         $tahunajar = DB::table('tahun_ajaran')
        ->where('id', $id)
        ->first();

        return view('admin.master.tahunajar.edit',['tahunajar'=>$tahunajar]);
    } 

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string|max:100',
            'ukt' => 'required|string|max:100',
            'semester' => 'required|string|max:100',
            'status' => 'required|string|max:100',
        ],[
            'nama.required' => 'Nama Tahun Ajaran wajib diisi.',
            'ukt.required' => 'Level UKT wajib diisi.',
            'semester.required' => 'Semester wajib diisi.',
            'status.required' => 'Status wajib diisi.',
        ]);
        DB::table('tahun_ajaran')  
            ->where('id', $id)
            ->update([
            'nama' => $request->nama,'ukt'=>$request->ukt, 'semester'=>$request->semester,'status' => $request->status]);

        return redirect('/admin/tahunajar')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('tahun_ajaran')->where('id',$id)->delete();

        return redirect('/admin/tahunajar')->with("success","Data Berhasil Dihapus !");
    }
}
