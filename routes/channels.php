<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    // افحص إن المستخدم جزء من الشات
    return \App\Models\Chat::where('id', $chatId)
        ->whereHas('consultationRequest', function ($query) use ($user) {
            $query->where('user_id', $user->id)->orWhere('doctor_id', $user->id);
        })->exists();
});
