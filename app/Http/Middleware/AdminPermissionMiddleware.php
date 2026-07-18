<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user || $user->role_slug !== 'admin') {
            abort(403, 'Administrator privileges required.');
        }

        if (! $user->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
