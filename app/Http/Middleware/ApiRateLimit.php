<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;

class ApiRateLimit
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next)
    {
        // Allow 60 requests per minute per IP for API
        $key = 'api.' . $request->ip();
        
        if ($this->limiter->tooManyAttempts($key, 60, 1)) {
            return response()->json([
                'error' => 'Too many requests. Please try again later.',
            ], 429);
        }

        $this->limiter->hit($key, 1);

        return $next($request);
    }
}