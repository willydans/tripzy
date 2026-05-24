<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // 🟢 FIX: Ubah pembacaan role jadi huruf kecil dan hapus spasi tersembunyi
        // Jadi mau di database ditulis "Admin", "ADMIN", atau "admin", tetep lolos!
        if (Auth::check() && strtolower(trim(Auth::user()->role)) === 'admin') {
            return $next($request);
        }
        
        // Kalau bukan admin, tendang ke 403 (Forbidden) atau home
        abort(403, 'Lu bukan admin bre, dilarang masuk!');
    }
}