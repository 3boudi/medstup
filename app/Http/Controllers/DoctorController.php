<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ConsultationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    public function profile(Request $request)
    {
        return response()->json($request->user()->load('clinic', 'specializations'));
    }

    public function pendingConsultationRequests(Request $request)
    {
        $doctor = $request->user();

        $requests = ConsultationRequest::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->with('user') // لجلب معلومات المستخدم
            ->get();

        return response()->json([
            'message' => 'Pending consultation requests',
            'requests' => $requests,
        ], 200);
    }


    public function respondToConsultation(Request $request, $requestId)
    {

        $doctor = $request->user();
        $requestData = ConsultationRequest::where('id', $requestId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$requestData) {
            return response()->json(['message' => 'Consultation request not found'], 404);
        }

        $action = $request->input('action'); // accept or reject

        if ($action === 'accept') {
            $requestData->status = 'accepted';
            $requestData->responded_at = now();
            $requestData->save();

            $chat = Chat::create([
                'consultation_request_id' => $requestData->id,
            ]);

            return response()->json([
                'message' => 'Consultation accepted and chat started',
                'chat' => $chat,
            ], 200);
        } elseif ($action === 'reject') {
            $requestData->status = 'rejected';
            $requestData->responded_at = now();
            $requestData->save();

            return response()->json(['message' => 'Consultation rejected'], 200);
        }

        return response()->json(['message' => 'Invalid action'], 400);
    }
    public function closeChat(Request $request, $chatId)
    {
        $doctor = $request->user();

        $chat = Chat::where('id', $chatId)->whereHas('consultationRequest', function ($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id);
        })->first();

        if (!$chat) {
            return response()->json(['message' => 'Chat not found'], 404);
        }

        if ($chat->is_closed) {
            return response()->json(['message' => 'Chat already closed'], 400);
        }

        $chat->is_closed = true;
        $chat->closed_at = now();
        $chat->save();

        return response()->json(['message' => 'Chat closed successfully'], 200);
    }
  
    public function doctorChats(Request $request)
    {
        $doctor = $request->user(); // لازم تتأكد من الحارس doctor.guard

        $chats = Chat::with(['consultationRequest.user', 'messages'])
            ->whereHas('consultationRequest', function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->id);
            })
            ->orderByDesc('id')
            ->get();

        return response()->json(['chats' => $chats]);
    }
}
