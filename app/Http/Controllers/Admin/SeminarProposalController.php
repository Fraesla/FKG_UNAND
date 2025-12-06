<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class SeminarProposalController extends Controller
{
    public function read(Request $request, $id_prodi = null)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai join + paginate
        $query = DB::table('seminar_proposal')
            ->join('mahasiswa', 'seminar_proposal.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('prodi', 'seminar_proposal.id_prodi', '=', 'prodi.id')
            ->select(
                'seminar_proposal.*',
                'prodi.nama as prodi',
                'mahasiswa.nama as nama',
                'mahasiswa.nobp',
            )
            ->orderBy('seminar_proposal.id', 'DESC');
            
        // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('seminar_proposal.id_prodi', $id_prodi);
        }

        // Supaya pagination tetap bawa query string (search / entries)
        $seminar_proposal = $query->paginate($entries);
        $seminar_proposal->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.seminarproposal.index', compact('seminar_proposal','username','id_prodi'));
    }

    public function feature(Request $request, $id_prodi = null)
    {
        $query = DB::table('seminar_proposal')
            ->join('mahasiswa', 'seminar_proposal.id_mahasiswa', '=', 'mahasiswa.id')
            ->join('prodi', 'seminar_proposal.id_prodi', '=', 'prodi.id')
            ->select(
                'seminar_proposal.*',
                'prodi.nama as prodi',
                'mahasiswa.nama as nama',
                'mahasiswa.nobp',
            );

        // Filter berdasarkan prodi
        if ($id_prodi) {
            $query->where('seminar_proposal.id_prodi', $id_prodi);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('seminar_proposal.id', 'like', "%{$search}%")
                  ->orWhere('prodi.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nama', 'like', "%{$search}%")
                  ->orWhere('mahasiswa.nobp', 'like', "%{$search}%")
                  ->orWhere('seminar_proposal.dosen_pembimbing_1', 'like', "%{$search}%")
                  ->orWhere('seminar_proposal.dosen_pembimbing_2', 'like', "%{$search}%")
                  ->orWhere('seminar_proposal.penguji_1', 'like', "%{$search}%")
                  ->orWhere('seminar_proposal.penguji_2', 'like', "%{$search}%")
                  ->orWhere('seminar_proposal.penguji_3', 'like', "%{$search}%");
            });
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $seminar_proposal = $query->orderBy('seminar_proposal.id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $seminar_proposal->appends($request->all());
        $username = auth()->user()->username;

        return view('admin.seminarproposal.index', compact('seminar_proposal','username','id_prodi'));
    }

    public function add($id_prodi = null){
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        return view('admin.seminarproposal.create',['mahasiswa'=>$mahasiswa,'dosen'=>$dosen,'id_prodi'=>$id_prodi]);
    }

    public function create(Request $request){

        // Validasi
        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'penguji_1' => 'required|string|max:255',
            'penguji_2' => 'required|string|max:255',
            'penguji_3' => 'required|string|max:255',

            // field file (nullable karena user bisa upload sebagian)
            'surat_proposal' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'file_draft'     => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'bukti_izin'     => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'lembar_jadwal'  => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ],[
            'id_mahasiswa.exists'       => 'Mahasiswa yang dipilih tidak valid.',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid.',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid.',
            'penguji_1.required'            => 'Penguji 1 wajib diisi.',
            'penguji_2.required'            => 'Penguji 2 wajib diisi.',
            'penguji_3.required'            => 'Penguji 3 wajib diisi.',
        ]);

        // Simpan file jika ada
        $paths = [];
        foreach (['surat_proposal','file_draft','bukti_izin','lembar_jadwal'] as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('seminar_proposal', 'public');
            } else {
                $paths[$field] = '';
            }
        }

        // Insert ke database
        DB::table('seminar_proposal')->insert([
            'id_mahasiswa' => $request->id_mahasiswa,
            'id_prodi' => $request->id_prodi,
            'dosen_pembimbing_1' => $request->dosen_pembimbing_1,
            'dosen_pembimbing_2' => $request->dosen_pembimbing_2,
            'penguji_1' => $request->penguji_1,
            'penguji_2' => $request->penguji_2,
            'penguji_3' => $request->penguji_3,
            'surat_proposal' => $paths['surat_proposal'],
            'file_draft' => $paths['file_draft'],
            'bukti_izin' => $paths['bukti_izin'],
            'lembar_jadwal' => $paths['lembar_jadwal'],
            'created_at' => now(),
        ]);

        return redirect('/admin/seminarproposal/'.$request->id_prodi)->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $seminar_proposal = DB::table('seminar_proposal')->where('id',$id)->first();
        $mahasiswa = DB::table('mahasiswa')->orderBy('id','DESC')->get();
        $dosen = DB::table('dosen')->orderBy('id','DESC')->get();
        
        return view('admin.seminarproposal.edit',['seminar_proposal'=>$seminar_proposal,'mahasiswa'=>$mahasiswa,'dosen'=>$dosen]);
    }

    public function update(Request $request, $id) {

        $request->validate([
            'id_mahasiswa' => 'required|exists:mahasiswa,id',
            'dosen_pembimbing_1' => 'required|exists:dosen,nama',
            'dosen_pembimbing_2' => 'required|exists:dosen,nama',
            'penguji_1' => 'required|string|max:255',
            'penguji_2' => 'required|string|max:255',
            'penguji_3' => 'required|string|max:255',

            // field file (nullable karena user bisa upload sebagian)
            'surat_proposal' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'file_draft'     => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'bukti_izin'     => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'lembar_jadwal'  => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ],[
            'id_mahasiswa.exists'       => 'Mahasiswa yang dipilih tidak valid.',
            'dosen_pembimbing_1.exists' => 'Dosen Bimbingan 1 yang dipilih tidak valid.',
            'dosen_pembimbing_2.exists' => 'Dosen Bimbingan 2 yang dipilih tidak valid.',
            'penguji_1.required'            => 'Penguji 1 wajib diisi.',
            'penguji_2.required'            => 'Penguji 2 wajib diisi.',
            'penguji_3.required'            => 'Penguji 3 wajib diisi.',
        ]);

        // ambil data lama
        $seminar_proposal = DB::table('seminar_proposal')->where('id', $id)->first();

        $updateData = [
            'id_mahasiswa' => $request->id_mahasiswa,
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

        return redirect('/admin/seminarproposal/'.$request->id_prodi)->with("success","Data Berhasil Diupdate !");
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

        return redirect('/admin/seminarproposal/'.$seminar_proposal->id_prodi)->with("success","Data Berhasil Dihapus !");
    }
}
