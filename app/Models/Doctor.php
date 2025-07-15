<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Testing\Fluent\Concerns\Has;
use \Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Authenticatable
{
     use HasApiTokens, Notifiable;
     
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'password',
        'remember_token',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function clinic()
{
    return $this->belongsTo(Clinic::class, 'clinic_id');
}


    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'doctor_specialization');
    }

 public function consultationRequests()
    {
        return $this->hasMany(ConsultationRequest::class);
    }
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }
}
