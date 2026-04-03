<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('token')) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }
        return $next($request);
    }
}
