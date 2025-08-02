<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Doctor;

class AuthenticateDoctor
{
    public function handle(Request $request, Closure $next)
    {
        $accessToken = $request->bearerToken();

        if (! $accessToken) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        $token = PersonalAccessToken::findToken($accessToken);

        if (! $token || ! $token->tokenable instanceof Doctor) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Additional security: Check if token is expired
        if ($token->expires_at && $token->expires_at->isPast()) {
            $token->delete();
            return response()->json(['error' => 'Token expired'], 401);
        }

        // Check if doctor is still approved
        if ($token->tokenable->status !== 'accepted') {
            $token->delete();
            return response()->json(['error' => 'Account no longer approved'], 401);
        }

        // ثبت المستخدم
        $request->setUserResolver(fn () => $token->tokenable);

        // 🧠 ضيف هذا: خزّن التوكن داخل request
        $request->attributes->set('accessToken', $token);

        return $next($request);
    }
}