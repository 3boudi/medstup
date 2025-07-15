<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSpecialization extends Model
{
    use HasFactory;

    protected $table = 'doctor_specialization'; // ضروري لأن Laravel يتوقع الاسم يكون بصيغة الجمع

    protected $fillable = [
        'doctor_id',
        'specialization_id',
        
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
}
