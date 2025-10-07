<?php

namespace App\Http\Controllers\Admin;

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
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function keluar()
    {
        Auth::logout();
    
        // Redirect user ke halaman login atau halaman lainnya
        return redirect()->route('login')->with("error","Akun Anda Belum Diaktifkan !");
    }
}
