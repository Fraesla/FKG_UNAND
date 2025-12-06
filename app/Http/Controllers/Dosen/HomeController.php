<?php

namespace App\Http\Controllers\Dosen;

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
        $dosen = DB::table('dosen')->where('nip', $username)->first();
        return view('dosen.dashboard.index', ['dosen'  => $dosen]);
    }

    public function profile(Request $request)
    {
        $username = auth()->user()->username;
        $dosen = DB::table('dosen')->where('nip', $username)->first();

        return view('dosen.dashboard.edit', ['dosen'  => $dosen]);
    }

    public function update(Request $request, $id) {
        
        $dosen = DB::table('dosen')->where('id',$id)->first();

        // Validasi input
        $request->validate([
            'nama' => 'required |string|max:255',
            'nip' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // maksimal 2MB
        ],[
            'nip.required' => 'NIP wajib diisi.',
            'nama.required' => 'Nama Dosen wajib diisi.',
            'gender.required' => 'Jenis Kelamin wajib diisi.',        
        ]);

        $dataUpdate = [
            'nip' => $request->nip,
            'nama' => $request->nama,
            'gender' => $request->gender,
            'contact' => $request->contact,
            'alamat' => $request->alamat,
        ];

        // kalau ada upload foto baru
        $path = null;
        if ($request->hasFile('foto')) {
            if ($dosen->foto && file_exists(storage_path('app/public/'.$dosen->foto))) {
                unlink(storage_path('app/public/'.$dosen->foto));
            }

            $path = $request->file('foto')->store('foto_dosen', 'public');
            $dataUpdate['foto'] = $path;
        }

        DB::table('dosen')
            ->where('id', $id)
            ->update($dataUpdate);

        DB::table('user')
            ->where('username', $dosen->nip)     // cari berdasarkan NIP lama
            ->update([
                'username' => $request->nip       // update ke NIP baru
            ]);

        return redirect('/dosen/profile')->with("success","Data Berhasil Diupdate !");
    }

    public function changepass()
    {
        $username = auth()->user()->username;
        $dosen = DB::table('dosen')->where('nip', $username)->first();
        return view('dosen.dashboard.changepass', ['dosen'  => $dosen]);
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
