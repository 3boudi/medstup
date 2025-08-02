<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['consultation_request_id', 'is_closed', 'closed_at'];

    protected function casts(): array
    {
        return [
            'is_closed' => 'boolean',
            'closed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function consultationRequest()
    {
        return $this->belongsTo(ConsultationRequest::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function getDoctorAttribute()
{
    return $this->consultationRequest->doctor;
}

public function getUserAttribute()
{
    return $this->consultationRequest->user;
}

}
