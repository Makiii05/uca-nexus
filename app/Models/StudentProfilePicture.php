<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfilePicture extends Model
{
    protected $fillable = [
        'student_id',
        'filename',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
