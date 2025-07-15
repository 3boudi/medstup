<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetGuard
{
    public function handle(Request $request, Closure $next, $guard)
    {
        Auth::shouldUse($guard); // ✅ يضبط الحارس المناسب (admin, doctor, user)
        return $next($request);
    }
}
