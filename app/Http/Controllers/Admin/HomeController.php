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
        $username = auth()->user()->username;
        return view('admin.dashboard.index', ['username'  => $username]);
    }

    public function user(Request $request){

        // Ambil parameter entries dari request, default = 5
        $entries = $request->input('entries', 5);

        // Query pakai paginate
        $user = DB::table('user')
                    ->orderBy('id', 'DESC')
                    ->paginate($entries);

        // Supaya pagination tetap bawa query string (search / entries)
        $user->appends($request->all());

        return view('admin.dashboard.user', ['user' => $user]);
    }

    public function feature(Request $request)
    {
        $query = DB::table('user');

        // Search berdasarkan ID/Nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%");
        }

        // Show entries (default 10)
        $entries = $request->get('entries', 10);

        // Ambil data
        $user = $query->orderBy('id', 'DESC')->paginate($entries);

        // Biar pagination tetap bawa query string
        $user->appends($request->all());

        return view('admin.dashboard.user', compact('user'));
    }

    public function add(){
        return view('admin.dashboard.create');
    }

    public function create(Request $request){
        $request->validate([
            'username' => 'required|string|max:100',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'level' => 'required|exists:user'
        ],[
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password diisi dulu.',
            'confirm_password.required' => 'Konfirmasi Password  diisi dulu.',
            'confirm_password.same' => 'Konfirmasi Password harus sama dengan Password baru yang tadi diinputkan.',
            'level.exists'   => 'Status dipilih tidak valid.',
            'level.required'   => 'Status dipilih dulu.',
        ]);

        DB::table('user')->insert([  
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
            'status' => '0',
        ]);

        return redirect('/admin/user')->with("success","Data Berhasil Ditambah !");
    } 

    public function edit($id){
        $user = DB::table('user')->where('id',$id)->first();
        
        return view('admin.dashboard.edit',['user'=>$user]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'username' => 'required|string|max:100',
            'level' => 'required|exists:user',
        ],[
            'username.required' => 'Username wajib diisi.',
            'level.required'   => 'Status dipilih dulu.',
            'level.exists'   => 'Status yang dipilih tidak valid.',
        ]);
        DB::table('user')  
            ->where('id', $id)
            ->update([
            'username' => $request->username,
            'level' => $request->level]);

        return redirect('/admin/user')->with("success","Data Berhasil Diupdate !");
    }

    public function changepass()
    {
        $id = auth()->user()->id;
        $user = DB::table('user')->where('id', $id)->first();
        return view('admin.dashboard.changepass', ['user'  => $user]);
    }

    public function ubahpass($id)
    {
        $user = DB::table('user')->where('id', $id)->first();
        return view('admin.dashboard.changepass', ['user'  => $user]);
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

        $user = DB::table('user')->where('id',$request->id)->first();

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

        return redirect('/admin/user')->with('success', 'Password berhasil diperbarui!');
    }

    public function delete($id)
    {
        DB::table('user')->where('id',$id)->delete();

        return redirect('/admin/user')->with("success","Data Berhasil Dihapus !");
    }

    public function keluar()
    {
        Auth::logout();
    
        // Redirect user ke halaman login atau halaman lainnya
        return redirect()->route('login')->with("error","Akun Anda Belum Diaktifkan !");
    }
}
