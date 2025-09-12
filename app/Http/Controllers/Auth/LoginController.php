<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class LoginController extends Controller
{

    public function index(){
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password', 'level');

        if (Auth::attempt($credentials)) {
            // Jika autentikasi berhasil
            return redirect()->intended('/admin/dashboard');
        } else {
            return redirect()->route('login')->with("error","Username, Password atau Status Salah !");
        }

        // Jika autentikasi gagal
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with("success","Anda Berhasil Logout!");
    }
}
