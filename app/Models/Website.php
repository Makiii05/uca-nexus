<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'type',
        'image_url',
        'title',
        'description',
        'event_date',
        'location',
        'days',
        'is_open',
        'start_time',
        'end_time',
        'embedded_url',
        'email',
        'contact',
        'social_link'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_open' => 'boolean'
    ];
}
