<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmasiMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Allow farmasi and admin roles
        if (!Auth::check() || !in_array(Auth::user()->role, ['farmasi', 'admin'])) {
            return redirect('/')->with('error', 'Hanya petugas farmasi atau admin yang dapat mengakses halaman ini.');
        }
        return $next($request);
    }
}
