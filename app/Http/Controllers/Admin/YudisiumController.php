<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Auth;

class YudisiumController extends Controller
{
    public function read(Request $request)
    {
        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $yudisium = DB::table('yudisium')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $yudisium->appends($request->all());

        return view('admin.yudisium.index', ['yudisium' => $yudisium]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('yudisium');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('no_bp', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('tgl_semi_proposal', 'like', "%{$search}%")
                  ->orWhere('tgl_semi_hasil', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $yudisium = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $yudisium->appends($request->all());

        return view('admin.yudisium.index', compact('yudisium'));
    }

    public function add(){
        return view('admin.yudisium.create');
    }

    public function create(Request $request){

        $request->validate([
        'hasil_turnitin' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'bukti_lunas' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'khs' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'kbs' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'brsempro' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'brsemhas' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'full_skripsi' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'matriks' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'toefl' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        // tambahkan validasi field lain
        ]);

        // Simpan file ke storage/Seminar Hasil
        $hasil = $request->file('hasil_turnitin')->store('yudisium', 'public');
        $bukti = $request->file('bukti_lunas')->store('yudisium', 'public');
        $khs = $request->file('khs')->store('yudisium', 'public');
        $kbs = $request->file('kbs')->store('yudisium', 'public');
        $brsempro = $request->file('brsempro')->store('yudisium', 'public');
        $brsemhas = $request->file('brsemhas')->store('yudisium', 'public');
        $skripsi = $request->file('full_skripsi')->store('yudisium', 'public');
        $matriks = $request->file('matriks')->store('yudisium', 'public');
        $toefl = $request->file('toefl')->store('yudisium', 'public');

        DB::table('yudisium')->insert([  
            'nama' => $request->nama,
            'no_bp' => $request->no_bp,
            'judul' => $request->judul,
            'tgl_semi_proposal' => $request->tgl_semi_proposal,
            'tgl_semi_hasil' => $request->tgl_semi_hasil,
            'hasil_turnitin' => $hasil,
            'bukti_lunas' => $bukti,
            'khs' => $khs,
            'kbs' => $kbs,
            'brsempro' => $brsempro,
            'brsemhas' => $brsemhas,
            'full_skripsi' => $skripsi,
            'matriks' => $matriks,
            'toefl' => $toefl
        ]);

        return redirect('/admin/yudisium')->with("success","Data Berhasil Ditambah !");
    }

    public function edit($id){
        $yudisium = DB::table('yudisium')->where('id',$id)->first();
        
        return view('admin.yudisium.edit',['yudisium'=>$yudisium]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_bp' => 'required|string|max:20',
            'judul' => 'required|string|max:20',
            'tgl_semi_proposal' => 'required|string|max:255',
            'tgl_semi_hasil' => 'nullable|string|max:255',

            // file rules
            'hasil_turnitin' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'bukti_lunas' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'khs' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'kbs' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'brsempro' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'brsemhas' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'full_skripsi' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'matriks' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            'toefl' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        // ambil data lama
        $yudisium = DB::table('yudisium')->where('id', $id)->first();

        $updateData = [
            'nama' => $request->nama,
            'no_bp' => $request->no_bp,
            'judul' => $request->judul,
            'tgl_semi_proposal' => $request->tgl_semi_proposal,
            'tgl_semi_hasil' => $request->tgl_semi_hasil,
        ];

        // === Hasil Turnitin ===
        if ($request->hasFile('hasil_turnitin')) {
            // hapus file lama
            if ($yudisium->hasil_turnitin && Storage::disk('public')->exists($yudisium->hasil_turnitin)) {
                Storage::disk('public')->delete($yudisium->hasil_turnitin);
            }

            // upload file baru
            $path = $request->file('hasil_turnitin')->store('hasil_turnitin', 'public');
            $updateData['hasil_turnitin'] = $path;
        }

        // === Bukti Lunas ===
        if ($request->hasFile('bukti_lunas')) {
            // hapus file lama
            if ($yudisium->bukti_lunas && Storage::disk('public')->exists($yudisium->bukti_lunas)) {
                Storage::disk('public')->delete($yudisium->bukti_lunas);
            }

            // upload file baru
            $path = $request->file('bukti_lunas')->store('bukti_lunas', 'public');
            $updateData['bukti_lunas'] = $path;
        }

        // === KHS ===
        if ($request->hasFile('khs')) {
            // hapus file lama
            if ($yudisium->khs && Storage::disk('public')->exists($yudisium->khs)) {
                Storage::disk('public')->delete($yudisium->khs);
            }

            // upload file baru
            $path = $request->file('khs')->store('khs', 'public');
            $updateData['khs'] = $path;
        }

        // === KBS ===
        if ($request->hasFile('kbs')) {
            // hapus file lama
            if ($yudisium->kbs && Storage::disk('public')->exists($yudisium->kbs)) {
                Storage::disk('public')->delete($yudisium->kbs);
            }

            // upload file baru
            $path = $request->file('kbs')->store('kbs', 'public');
            $updateData['kbs'] = $path;
        }

        // === Brsempro ===
        if ($request->hasFile('brsempro')) {
            // hapus file lama
            if ($yudisium->brsempro && Storage::disk('public')->exists($yudisium->brsempro)) {
                Storage::disk('public')->delete($yudisium->brsempro);
            }

            // upload file baru
            $path = $request->file('brsempro')->store('brsempro', 'public');
            $updateData['brsempro'] = $path;
        }

        // === Brsemhas ===
        if ($request->hasFile('brsemhas')) {
            // hapus file lama
            if ($yudisium->brsemhas && Storage::disk('public')->exists($yudisium->brsemhas)) {
                Storage::disk('public')->delete($yudisium->brsemhas);
            }

            // upload file baru
            $path = $request->file('brsemhas')->store('brsemhas', 'public');
            $updateData['brsemhas'] = $path;
        }

        // === Full skripsi ===
        if ($request->hasFile('full_skripsi')) {
            // hapus file lama
            if ($yudisium->full_skripsi && Storage::disk('public')->exists($yudisium->full_skripsi)) {
                Storage::disk('public')->delete($yudisium->full_skripsi);
            }

            // upload file baru
            $path = $request->file('full_skripsi')->store('full_skripsi', 'public');
            $updateData['full_skripsi'] = $path;
        }

        // === Matriks ===
        if ($request->hasFile('matriks')) {
            // hapus file lama
            if ($yudisium->matriks && Storage::disk('public')->exists($yudisium->matriks)) {
                Storage::disk('public')->delete($yudisium->matriks);
            }

            // upload file baru
            $path = $request->file('matriks')->store('matriks', 'public');
            $updateData['matriks'] = $path;
        }


        // === TOEFL ===
        if ($request->hasFile('toefl')) {
            // hapus file lama
            if ($yudisium->toefl && Storage::disk('public')->exists($yudisium->toefl)) {
                Storage::disk('public')->delete($yudisium->toefl);
            }

            // upload file baru
            $path = $request->file('toefl')->store('toefl', 'public');
            $updateData['toefl'] = $path;
        }

        // update DB
        DB::table('yudisium')->where('id', $id)->update($updateData);


        return redirect('/admin/yudisium')->with("success","Data Berhasil Diupdate !");
    }

    public function delete($id)
    {
        // ambil data yudisium
        $yudisium = DB::table('yudisium')->where('id', $id)->first();

        if ($yudisium) {
            // hapus file Hasil Turnitin kalau ada
            if ($yudisium->hasil_turnitin && Storage::disk('public')->exists($yudisium->hasil_turnitin)) {
                Storage::disk('public')->delete($yudisium->hasil_turnitin);
            }

            // hapus file Bukti Lunas kalau ada
            if ($yudisium->bukti_lunas && Storage::disk('public')->exists($yudisium->bukti_lunas)) {
                Storage::disk('public')->delete($yudisium->bukti_lunas);
            }

            // hapus file KHS kalau ada
            if ($yudisium->khs && Storage::disk('public')->exists($yudisium->khs)) {
                Storage::disk('public')->delete($yudisium->khs);
            }

            // hapus file KBS kalau ada
            if ($yudisium->kbs && Storage::disk('public')->exists($yudisium->kbs)) {
                Storage::disk('public')->delete($yudisium->kbs);
            }

            // hapus file Brsempro kalau ada
            if ($yudisium->brsempro && Storage::disk('public')->exists($yudisium->brsempro)) {
                Storage::disk('public')->delete($yudisium->brsempro);
            }

            // hapus file Brsemhas kalau ada
            if ($yudisium->brsemhas && Storage::disk('public')->exists($yudisium->brsemhas)) {
                Storage::disk('public')->delete($yudisium->brsemhas);
            }

            // hapus file Full Skripsi kalau ada
            if ($yudisium->full_skripsi && Storage::disk('public')->exists($yudisium->full_skripsi)) {
                Storage::disk('public')->delete($yudisium->full_skripsi);
            }

            // hapus file Matriks kalau ada
            if ($yudisium->matriks && Storage::disk('public')->exists($yudisium->matriks)) {
                Storage::disk('public')->delete($yudisium->matriks);
            }

            // hapus file TOEFL kalau ada
            if ($yudisium->toefl && Storage::disk('public')->exists($yudisium->toefl)) {
                Storage::disk('public')->delete($yudisium->toefl);
            }

            // hapus data dari tabel
            DB::table('yudisium')->where('id', $id)->delete();
        }

        DB::table('yudisium')->where('id',$id)->delete();

        return redirect('/admin/yudisium')->with("success","Data Berhasil Dihapus !");
    }
}
