<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('user')->check() || Auth::guard('user')->user()->level !== 'Superadmin') {
            abort(403, 'Unauthorized access');
        }
        
        return $next($request);
    }
}
