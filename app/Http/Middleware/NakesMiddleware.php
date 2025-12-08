<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NakesMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $role = Auth::user()->role;
        // Allow perawat, dokter, and admin to access nakes routes
        if (!in_array($role, ['perawat', 'dokter', 'admin','rekam_medis'])) {
            return redirect('/')->with('error', 'Hanya tenaga kesehatan (perawat/dokter) atau admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
