<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SeminarProposalController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $seminar_proposal = DB::table('seminar_proposal')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $seminar_proposal->appends($request->all());

        return view('admin.seminarproposal.index', ['seminar_proposal' => $seminar_proposal]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('seminar_proposal');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('no_bp', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('penguji_1', 'like', "%{$search}%")
                  ->orWhere('penguji_2', 'like', "%{$search}%")
                  ->orWhere('penguji_3', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $seminar_proposal = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $seminar_proposal->appends($request->all());

        return view('admin.seminarproposal.index', compact('seminar_proposal'));
    }

    public function add(){
        return view('admin.seminarproposal.create');
    }

    public function create(Request $request){
        DB::table('seminar_proposal')->insert([  
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'no_hp' => $request->no_hp,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'penguji_1' => $request->penguji_1,
            'penguji_2' => $request->penguji_2,
            'penguji_3' => $request->penguji_3,
            'surat_proposal' => $request->surat_proposal,
            'file_draft' => $request->file_draft,
            'bukti_izin' => $request->bukti_izin,
            'lembar_jadwal' => $request->lembar_jadwal
        ]);

        return redirect('/admin/seminarproposal')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $seminar_proposal = DB::table('seminar_proposal')->where('id',$id)->first();
        
        return view('admin.seminarproposal.edit',['seminar_proposal'=>$seminar_proposal]);
    }

    public function update(Request $request, $id) {
        DB::table('seminar_proposal')  
            ->where('id', $id)
            ->update([
            'nama_' => $request->nama,
            'no_bp' => $request->no_bp,
            'no_hp' => $request->no_hp,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'penguji_1' => $request->penguji_1,
            'penguji_2' => $request->penguji_2,
            'penguji_3' => $request->penguji_3,
            'surat_proposal' => $request->surat_proposal,
            'file_draft' => $request->file_draft,
            'bukti_izin' => $request->bukti_izin,
            'lembar_jadwal' => $request->lembar_jadwal
        ]);

        return redirect('/admin/seminarproposal')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        DB::table('seminar_proposal')->where('id',$id)->delete();

        return redirect('/admin/seminarproposal')->with("success","Data Berhasil Dihapus !");
    }
}
