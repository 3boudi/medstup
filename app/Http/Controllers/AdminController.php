<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Doctor;

class AdminController extends Controller
{
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
    
    public function profile(Request $request)
    {
        $admin = $request->user();
        return response()->json($admin);
    }
}
