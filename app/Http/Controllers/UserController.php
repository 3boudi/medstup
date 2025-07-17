<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'must be logged in'], 401);
        }
        return response()->json([
            'message' => 'User profile retrieved successfully',
            'user' => $user
        ], 200);
    }
}
