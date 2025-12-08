<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekamMedisMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Allow rekam_medis and admin roles
        if (!Auth::check() || !in_array(Auth::user()->role, ['rekam_medis', 'admin'])) {
            return redirect('/')->with('error', 'Hanya petugas rekam medis atau admin yang dapat mengakses halaman ini.');
        }
        return $next($request);
    }
}
