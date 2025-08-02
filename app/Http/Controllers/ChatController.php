<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function sendMessage(Request $request, $chatId)
{
    $chat = Chat::findOrFail($chatId);

    if ($chat->is_closed) {
        return response()->json(['error' => 'Chat is closed.'], 403);
    }

    $validated = $request->validate([
        'type' => 'required|in:text,image,file',
        'message' => 'nullable|string|max:1000',
        'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:10240', // 10MB max
    ]);

    // Additional security checks for file uploads
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        
        // Check file size (additional check)
        if ($file->getSize() > 10 * 1024 * 1024) { // 10MB
            return response()->json(['error' => 'File size too large. Maximum 10MB allowed.'], 422);
        }

        // Validate file extension matches MIME type
        $allowedMimes = [
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        if (!isset($allowedMimes[$extension]) || $allowedMimes[$extension] !== $mimeType) {
            return response()->json(['error' => 'Invalid file type.'], 422);
        }
    }

    $sender = $request->user();
    $senderType = $sender instanceof \App\Models\Doctor ? 'doctor' : 'user';

    // تأكد أن المرسل هو جزء من المحادثة
    $isParticipant = $chat->consultationRequest->user_id == $sender->id || $chat->consultationRequest->doctor_id == $sender->id;
    if (!$isParticipant) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $filePath = null;
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        
        // Generate secure filename
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid('chat_', true) . '.' . $extension;
        
        // Store with secure filename
        $path = $file->storeAs('chat_files', $filename, 'public');
        $filePath = [$path];
    }

    $message = Message::create([
        'chat_id' => $chat->id,
        'sender_type' => $senderType,
        'sender_id' => $sender->id,
        'type' => $validated['type'],
        'message' => $validated['message'] ?? null,
        'file_path' => $filePath,
        'sent_at' => now(),
    ]);

    broadcast(new \App\Events\MessageSent($message))->toOthers();

    return response()->json([
        'message' => 'Message sent',
        'data' => $message
    ]);
}

    public function closeChat($chatId)
    {
        $chat = Chat::findOrFail($chatId);

        if ($chat->is_closed) return response()->json(['message' => 'Already closed']);

        $chat->update([
            'is_closed' => true,
            'closed_at' => now(),
        ]);

        return response()->json(['message' => 'Chat closed']);
    }
    public function getMessages($chatId)
    {
        $chat = Chat::with(['messages.sender'])->findOrFail($chatId);

        // تحقق أن المستخدم له علاقة بالمحادثة
        $user = request()->user();
        $isParticipant = $chat->consultationRequest->user_id == $user->id || $chat->consultationRequest->doctor_id == $user->id;

        if (!$isParticipant) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

       return response()->json([
    'chat' => [
        'id' => $chat->id,
        'is_closed' => $chat->is_closed,
        'user' => $chat->consultationRequest->user->only(['id', 'name']),
        'doctor' => $chat->consultationRequest->doctor->only(['id', 'name']),
    ],
    'messages' => $chat->messages()->orderBy('sent_at', 'asc')->get()
]);

    }
}
