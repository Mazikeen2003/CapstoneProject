<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequirePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $allowedRoutes = ['password.change', 'password.change.submit', 'logout'];

        if ($user->must_change_password && ! in_array($request->route()?->getName(), $allowedRoutes, true)) {
            return redirect()->route('password.change')->with('status', 'Please set a new password before continuing.');
        }

        return $next($request);
    }
}
