<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CityOfficialMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        if (! Auth::user()->hasRole('city')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
