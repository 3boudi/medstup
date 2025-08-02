<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    protected $fillable = ['user_id', 'doctor_id', 'status', 'responded_at'];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }
}
