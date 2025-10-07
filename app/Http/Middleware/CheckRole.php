<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Cek apakah role user sesuai dengan parameter middleware
        if (!in_array($user->level, $roles)) {
            // Kalau role salah → redirect sesuai role yang benar
            switch ($user->level) {
                case 'admin':
                    return redirect('/admin/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                case 'dosen':
                    return redirect('/dosen/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                case 'mahasiswa':
                    return redirect('/mahasiswa/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
                default:
                    return redirect()->route('login')->with('error', 'Role tidak dikenali.');
            }
        }

        return $next($request);
    }
}
