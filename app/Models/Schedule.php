<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'proctor_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'process',
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function proctor()
    {
        return $this->belongsTo(User::class, 'proctor_id');
    }

    // Alias for assigned person (admission staff or process facilitator)
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'proctor_id');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
