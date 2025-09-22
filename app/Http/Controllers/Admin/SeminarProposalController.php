<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $request->validate([
        'surat_proposal' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'file_draft' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'bukti_izin' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'lembar_jadwal' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        // tambahkan validasi field lain
        ]);

        // Simpan file ke storage/Seminar Proposal
        $surat = $request->file('surat_proposal')->store('seminar_proposal', 'public');
        $draft = $request->file('file_draft')->store('seminar_proposal', 'public');
        $izin = $request->file('bukti_izin')->store('seminar_proposal', 'public');
        $lembar = $request->file('lembar_jadwal')->store('seminar_proposal', 'public');

        DB::table('seminar_proposal')->insert([  
            'nama' => $request->nama,
            'no_bp' => $request->no_bp,
            'no_hp' => $request->no_hp,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'penguji_1' => $request->penguji_1,
            'penguji_2' => $request->penguji_2,
            'penguji_3' => $request->penguji_3,
            'surat_proposal' => $surat,
            'file_draft' => $draft,
            'bukti_izin' => $izin,
            'lembar_jadwal' => $lembar
        ]);

        return redirect('/admin/seminarproposal')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $seminar_proposal = DB::table('seminar_proposal')->where('id',$id)->first();
        
        return view('admin.seminarproposal.edit',['seminar_proposal'=>$seminar_proposal]);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'nama' => 'required|string|max:255',
            'no_bp' => 'required|string|max:20',
            'no_hp' => 'required|string|max:20',
            'dosen_pembimbing_1' => 'required|string|max:255',
            'dosen_pembimbing_2' => 'nullable|string|max:255',
            'penguji_1' => 'nullable|string|max:255',
            'penguji_2' => 'nullable|string|max:255',
            'penguji_3' => 'nullable|string|max:255',

            // file rules
            'surat_proposal' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'file_draft' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'bukti_izin' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'lembar_jadwal' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
        ]);

        // ambil data lama
        $seminar_proposal = DB::table('seminar_proposal')->where('id', $id)->first();

        $updateData = [
            'nama' => $request->nama,
            'no_bp' => $request->no_bp,
            'no_hp' => $request->no_hp,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'penguji_1' => $request->penguji_1,
            'penguji_2' => $request->penguji_2,
            'penguji_3' => $request->penguji_3,
        ];

        // === Surat Seminar Proposal ===
        if ($request->hasFile('surat_proposal')) {
            // hapus file lama
            if ($seminar_proposal->surat_proposal && Storage::disk('public')->exists($seminar_proposal->surat_proposal)) {
                Storage::disk('public')->delete($seminar_proposal->surat_proposal);
            }

            // upload file baru
            $path = $request->file('surat_proposal')->store('surat_proposal', 'public');
            $updateData['surat_proposal'] = $path;
        }

        // === File Draft ===
        if ($request->hasFile('file_draft')) {
            // hapus file lama
            if ($seminar_proposal->file_draft && Storage::disk('public')->exists($seminar_proposal->file_draft)) {
                Storage::disk('public')->delete($seminar_proposal->file_draft);
            }

            // upload file baru
            $path = $request->file('file_draft')->store('file_draft', 'public');
            $updateData['file_draft'] = $path;
        }

        // === File Draft ===
        if ($request->hasFile('lembar_jadwal')) {
            // hapus file lama
            if ($seminar_proposal->lembar_jadwal && Storage::disk('public')->exists($seminar_proposal->lembar_jadwal)) {
                Storage::disk('public')->delete($seminar_proposal->lembar_jadwal);
            }

            // upload file baru
            $path = $request->file('lembar_jadwal')->store('lembar_jadwal', 'public');
            $updateData['lembar_jadwal'] = $path;
        }

        // update DB
        DB::table('seminar_proposal')->where('id', $id)->update($updateData);

        // DB::table('seminar_proposal')  
        //     ->where('id', $id)
        //     ->update([
        //     'nama' => $request->nama,
        //     'no_bp' => $request->no_bp,
        //     'no_hp' => $request->no_hp,
        //     'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
        //     'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
        //     'penguji_1' => $request->penguji_1,
        //     'penguji_2' => $request->penguji_2,
        //     'penguji_3' => $request->penguji_3,
        //     'surat_proposal' => $request->surat_proposal,
        //     'file_draft' => $request->file_draft,
        //     'bukti_izin' => $request->bukti_izin,
        //     'lembar_jadwal' => $request->lembar_jadwal
        // ]);

        return redirect('/admin/seminarproposal')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        // ambil data seminar_proposal
        $seminar_proposal = DB::table('seminar_proposal')->where('id', $id)->first();

        if ($seminar_proposal) {
            // hapus file surat_seminar_proposal kalau ada
            if ($seminar_proposal->surat_proposal && Storage::disk('public')->exists($seminar_proposal->surat_proposal)) {
                Storage::disk('public')->delete($seminar_proposal->surat_proposal);
            }

            // hapus file file_draft kalau ada
            if ($seminar_proposal->file_draft && Storage::disk('public')->exists($seminar_proposal->file_draft)) {
                Storage::disk('public')->delete($seminar_proposal->file_draft);
            }

            // hapus file bukti_izin kalau ada
            if ($seminar_proposal->bukti_izin && Storage::disk('public')->exists($seminar_proposal->bukti_izin)) {
                Storage::disk('public')->delete($seminar_proposal->bukti_izin);
            }

            // hapus file lembar_jadwal kalau ada
            if ($seminar_proposal->lembar_jadwal && Storage::disk('public')->exists($seminar_proposal->lembar_jadwal)) {
                Storage::disk('public')->delete($seminar_proposal->lembar_jadwal);
            }

            // hapus data dari tabel
            DB::table('seminar_proposal')->where('id', $id)->delete();
        }

        return redirect('/admin/seminarproposal')->with("success","Data Berhasil Dihapus !");
    }
}
