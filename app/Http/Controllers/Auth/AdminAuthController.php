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


    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:admins,name',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(['message' => 'Admin created successfully', 'admin' => $admin], 201);
    }


    public function pendingDoctors()
    {
        $doctors = Doctor::where('status', 'pending')
            ->with(['clinic', 'specializations'])
            ->get();
        return response()->json($doctors);
    }
    // ✅ قبول طبيب
    public function acceptDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->status = 'accepted';
        $doctor->save();

        return response()->json(['message' => 'Doctor accepted']);
    }

    // ✅ رفض طبيب
    public function rejectDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->status = 'rejected';
        $doctor->save();

        return response()->json(['message' => 'Doctor rejected']);
    }
}
//4|ri5A0qoZTCTSA8AsrZiXmJkJaShnQ6S4qEsPrICEbe82cbae