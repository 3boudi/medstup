<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Admin;

class AuthenticateAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->bearerToken();

        if (! $accessToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $token = PersonalAccessToken::findToken($accessToken);

        if (! $token || $token->tokenable_type !== Admin::class) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->setUserResolver(fn () => $token->tokenable);

        return $next($request);
    }
}
