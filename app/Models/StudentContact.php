<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// StudentContact Model
class StudentContact extends Model
{
    protected $fillable = [
        'student_id',
        'zip_code',
        'present_address',
        'permanent_address',
        'telephone_number',
        'mobile_number',
        'email',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
