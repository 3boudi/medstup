<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json( 'Invalid credentials', 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->attributes->get('accessToken');

        if (! $token) {
            return response()->json(['message' => 'must be logged in'], 401);
        }

        $token->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // ✅ WEB ONLY
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        return $this->createOrLoginGoogleUser($googleUser);
    }


    // ✅ MOBILE ONLY (Flutter): Use access_token
    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {

            $googleUser = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false])) // Disable SSL verification if needed
                ->stateless()
                ->userFromToken($request->access_token);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid Google token', 'error' => $e->getMessage()], 401);
        }

        return $this->createOrLoginGoogleUser($googleUser);
    }

    private function createOrLoginGoogleUser($googleUser)
    {
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'email_verified_at' => now(),
                'password' => Hash::make(uniqid()),
            ]
        );

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
    
}
