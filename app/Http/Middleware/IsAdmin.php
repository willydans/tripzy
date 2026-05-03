<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        // Kalau bukan admin, tendang ke 403 (Forbidden) atau home
        abort(403, 'Lu bukan admin bre, dilarang masuk!');
    }
}