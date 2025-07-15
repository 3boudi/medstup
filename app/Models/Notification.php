<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    protected $fillable = ['notifiable_type', 'notifiable_id', 'type', 'data', 'read_at', 'created_at'];

    public function notifiable()
    {
        return $this->morphTo();
    }
    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
        'read_at' => 'datetime',
    ];
}

