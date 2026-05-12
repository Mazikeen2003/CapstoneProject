<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $userRole = session('mock_user.role');

        if ($userRole !== $role) {
            return redirect('/login');
        }

        return $next($request);
    }
}