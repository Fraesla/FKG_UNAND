<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Auth;

class LoginController extends Controller
{

    public function index()
    {

        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        // Validasi input dulu
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Ambil data user berdasarkan username
        $user = DB::table('user')->where('username', $request->username)->first();

        if ($user) {
            // Cek password sesuai hash
            if (Hash::check($request->password, $user->password)) {

                // Update status dari 0 -> 1 setelah login sukses
                if ($user->status == '0') {
                    DB::table('user')->where('id', $user->id)->update(['status' => '1']);
                }

                // Login user dengan Auth::loginUsingId
                Auth::loginUsingId($user->id);

                // Redirect sesuai level
                if ($user->level === 'mahasiswa') {
                    return redirect()->intended('/mahasiswa/dashboard');
                } elseif ($user->level === 'dosen') {
                    return redirect()->intended('/dosen/dashboard');
                } else {
                    return redirect()->intended('/admin/dashboard');
                }
            } else {
                return redirect()->route('login')->with("error", "Password salah!");
            }
        }

        return redirect()->route('login')->with("error", "Username tidak ditemukan!");
    } 

    public function logout(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        if ($user) {
            // Update status ke 0 (offline)
            DB::table('user')->where('id', $user->id)->update(['status' => '0']);
        }

        // Logout pakai Auth bawaan Laravel
        Auth::logout();

        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with("success", "Anda berhasil logout!");
    }
}
