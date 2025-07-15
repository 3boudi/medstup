<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    public $timestamps = false;

    protected $fillable = ['specialization'];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialization');
    }
}
