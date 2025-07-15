<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['consultation_request_id', 'is_closed', 'closed_at'];

    public function consultationRequest()
    {
        return $this->belongsTo(ConsultationRequest::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
}
