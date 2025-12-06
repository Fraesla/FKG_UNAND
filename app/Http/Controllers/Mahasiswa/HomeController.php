<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DatePeriod;
use DateInterval;
use DateTime;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $username = auth()->user()->username;
        $mahasiswa = DB::table('mahasiswa')->where('nobp', $username)->first();
        return view('mahasiswa.dashboard.index', ['mahasiswa'  => $mahasiswa]);
    }

    public function profile(Request $request)
    {
        $username = auth()->user()->username;

        $mahasiswa = DB::table('mahasiswa')
            ->leftJoin('tahun_ajaran', 'tahun_ajaran.id', '=', 'mahasiswa.id_tahun_ajaran')
            ->select('mahasiswa.*', 
                'tahun_ajaran.nama as tahun_ajaran', 
                'tahun_ajaran.semester as semester',
                'tahun_ajaran.ukt as ukt',
                'tahun_ajaran.status as status')
            ->where('nobp', $username)
            ->first();

        $tahun = DB::table('tahun_ajaran')->orderBy('id','DESC')->get();

        return view('mahasiswa.dashboard.edit', [
            'mahasiswa' => $mahasiswa,
            'tahun' => $tahun
        ]);
    }

    public function update(Request $request, $id) {
        
        $mahasiswa = DB::table('mahasiswa')->where('id',$id)->first();

        // Validasi input
        $request->validate([
            'nobp' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ],[
            'nobp.required' => 'NO.BP wajib diisi.',
            'nama.required' => 'Nama Lengkap Mahaiswa wajib diisi.',
            'gender.required' => 'Jenis Kelamin wajib diisi.',
        ]);

        $dataUpdate = [
            'nobp' => $request->nobp,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'contact' => $request->contact,
            'alamat' => $request->alamat,
            'id_tahun_ajaran' => $request->id_tahun_ajaran,
        ];

        // kalau ada upload foto baru
        $path = null;
        if ($request->hasFile('foto')) {
            if ($mahasiswa->foto && file_exists(storage_path('app/public/'.$mahasiswa->foto))) {
                unlink(storage_path('app/public/'.$mahasiswa->foto));
            }

            $path = $request->file('foto')->store('foto_mahasiswa', 'public');
            $dataUpdate['foto'] = $path;
        }

        DB::table('mahasiswa')
            ->where('id', $id)
            ->update($dataUpdate);

        DB::table('user')
            ->where('username', $mahasiswa->nobp)     // cari berdasarkan No.BP lama
            ->update([
                'username' => $request->nobp       // update ke No.BP baru
            ]);

        return redirect('/mahasiswa/profile')->with("success","Data Berhasil Diupdate !");
    }

    public function changepass()
    {
        $username = auth()->user()->username;
        $mahasiswa = DB::table('mahasiswa')->where('nobp', $username)->first();
        return view('mahasiswa.dashboard.changepass', ['mahasiswa'  => $mahasiswa]);
    }

    public function updatePass(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ],[
            'old_password.required' => 'Masukkan Password Lama diisi dulu.',
            'new_password.required' => 'Masukkan Password Baru  diisi dulu.',
            'confirm_password.required' => 'Konfirmasi Password Baru  diisi dulu.',
            'confirm_password.same' => 'Konfirmasi Password Baru harus sama dengan Password baru yang tadi diinputkan.',
        ]);

        $user = Auth::user();

        // cek password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama salah.']);
        }

        // update password baru
        DB::table('user')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->new_password)
            ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
