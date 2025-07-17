<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email_or_name' => 'required|string',
            'password' => 'required|string',
        ]);


        $admin = Admin::where('email', $request->email_or_name)
            ->orWhere('name', $request->email_or_name)
            ->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email_or_name' => ['Invalid credentials.'],
            ]);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'admin' => $admin,
            'message' => 'Login successful',
        ]);
    }



    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
//4|ri5A0qoZTCTSA8AsrZiXmJkJaShnQ6S4qEsPrICEbe82cbae