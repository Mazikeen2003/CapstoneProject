<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DepartmentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $userRole = session('mock_user.role');

        if ($userRole !== 'department') {
            return redirect('/login');
        }

        return $next($request);
    }
}