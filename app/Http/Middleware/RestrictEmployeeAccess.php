<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictEmployeeAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'employee') {
            return redirect()->route('dashboard')->with('error', '⛔ Accès refusé. Veuillez contacter l\'administrateur.');
        }

        return $next($request);
    }
}
