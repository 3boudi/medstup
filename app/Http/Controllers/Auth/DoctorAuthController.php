<?php

namespace App\Http\Controllers\Auth;

use App\Models\Doctor;
use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DoctorAuthController extends Controller
{
    // ✅ تسجيل الطبيب
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:doctors',
            'phone'           => 'nullable|string|max:20',
            'password'        => 'required|string|min:6|confirmed',
            'clinic_name'     => 'required|string|max:255',
            'location'        => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'specializations' => 'required|array|min:1', // ex: [1, 3, 5]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // إنشاء الطبيب بحالة pending
        $doctor = Doctor::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'status'   => 'pending',
        ]);

        $doctor->save();
        // ربط التخصصات
        $doctor->specializations()->attach($request->specializations);

        $clinic = Clinic::create([
            'name' => $request->clinic_name,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        $doctor->clinic_id = $clinic->id;
        $doctor->save();


        return response()->json([
            'message' => 'Your registration has been submitted. Please wait for admin approval.',
            'doctor'  => $doctor->load('clinic', 'specializations'),
        ], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $doctor = Doctor::where('email', $credentials['email'])->first();

        if (! $doctor || ! Hash::check($credentials['password'], $doctor->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($doctor->status !== 'accepted') {
            return response()->json(['message' => 'Your account is not approved yet'], 403);
        }

        $token = $doctor->createToken('doctor-token')->plainTextToken;

        return response()->json([
            'token'       => $token,
            'doctor'      => $doctor,
        ]);
    }

public function logout(Request $request)
{
    $token = $request->attributes->get('accessToken');

    if (! $token) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $token->delete();

    return response()->json(['message' => 'Logged out successfully']);
}
 
}
