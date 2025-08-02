<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['chat_id', 'sender_type', 'sender_id', 'type', 'message', 'file_path', 'sent_at'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // Polymorphic relationship for sender
    public function sender()
    {
        return $this->morphTo('sender', 'sender_type', 'sender_id');
    }

    // Define the morph map for better performance and security
    public function getMorphClass()
    {
        $morphMap = [
            'user' => \App\Models\User::class,
            'doctor' => \App\Models\Doctor::class,
        ];

        return array_search(static::class, $morphMap) ?: static::class;
    }

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'file_path' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
