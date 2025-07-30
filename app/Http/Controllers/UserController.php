<?php

namespace App\Http\Controllers;

use App\Models\ConsultationRequest;
use App\Models\Specialization;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Chat;

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
    public function showAllSpecializations(Request $request)
    {
        $specializations = Specialization::all();

        return response()->json([
            'message' => 'Specializations list ',
            'specializations' => $specializations
        ], 200);
    }
    public function doctorsBySpecialization($id)
    {

        $doctor = Doctor::whereHas('specializations', function ($query) use ($id) {
            $query->where('specialization_id', $id);
        })->with('clinic', 'specializations')->get();
        $specializations = Specialization::find($id);


        return response()->json([
            'specialization' => $specializations,
            'message' => 'Doctors by specialization retrieved successfully',
            'doctors' => $doctor

        ], 200);
    }
    public function doctorDetails($id)
    {
        $doctor = Doctor::with('clinic', 'specializations')->find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json([
            'message' => 'Doctor details retrieved successfully',
            'doctor' => $doctor
        ], 200);
    }
    public function  requestConsultation(Request $request, $doctorId)
    {
        $user = $request->user();

        $exists = ConsultationRequest::where('user_id', $user->id)
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending')->first();

        if ($exists) {
            return response()->json(['message' => 'Consultation already requested'], 400);
        }
        $consultationRequest = ConsultationRequest::create([
            'user_id' => $user->id,
            'doctor_id' => $doctorId,
            'status' => 'pending',
        ]);
        return response()->json([
            'message' => 'Consultation request created successfully',
            'consultationRequest' => $consultationRequest
        ], 201);
    }
    public function showAllReqeustsConsultation(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'must be logged in'], 401);
        }

        $requests = ConsultationRequest::where('user_id', $user->id)->with('doctor')->get();

        return response()->json([
            'message' => 'Consultation requests retrieved successfully',
            'requests' => $requests
        ], 200);
    }
    public function userChats(Request $request)
{
    $user = $request->user();

    $chats = Chat::with(['consultationRequest.doctor', 'messages'])
        ->whereHas('consultationRequest', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->orderByDesc('id')
        ->get();

    return response()->json(['chats' => $chats]);
}

}
