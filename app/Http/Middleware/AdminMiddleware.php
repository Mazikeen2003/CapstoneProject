<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $userRole = session('mock_user.role');

        if ($userRole !== 'admin') {
            return redirect('/login');
        }

        return $next($request);
    }
}