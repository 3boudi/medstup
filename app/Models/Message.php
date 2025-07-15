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

   //Polymorphic relationship for sender
    public function sender()
    {
        return $this->morphTo(__FUNCTION__, 'sender_type', 'sender_id');
    }   
    protected $casts = [
        'sent_at' => 'datetime',
        'file_path' => 'array', // Assuming file_path can store multiple file paths as an array
        'message' => 'string', // Ensure message is cast to string
    ];
}
