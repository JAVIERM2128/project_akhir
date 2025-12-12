<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user adalah admin
        // Untuk sementara kita gunakan email admin tertentu atau field role jika sudah ada
        // Jika field role belum ada di tabel users, kita asumsikan user dengan email tertentu adalah admin

        if (Auth::check()) {
            $user = Auth::user();
            // Asumsikan admin adalah user dengan email yang sudah ditentukan atau memiliki role admin
            // Kita akan menambahkan field role ke tabel users

            // Untuk sementara kita cek berdasarkan email admin
            // Kita akan buat migrasi tambahan nanti untuk field role
            if ($user->email === 'admin@example.com' || $user->role === 'admin') {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
