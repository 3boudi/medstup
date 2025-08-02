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
        'message' => 'nullable|string',
        'file' => 'nullable|file|mimes:jpeg,png,pdf,mp4',
    ]);

    $sender = $request->user();
    $senderType = $sender instanceof \App\Models\Doctor ? 'doctor' : 'user';

    // تأكد أن المرسل هو جزء من المحادثة
    $isParticipant = $chat->consultationRequest->user_id == $sender->id || $chat->consultationRequest->doctor_id == $sender->id;
    if (!$isParticipant) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $filePath = null;
    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('chat_files', 'public');
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
