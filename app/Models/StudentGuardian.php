<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// StudentGuardian Model
class StudentGuardian extends Model
{
    protected $fillable = [
        'student_id',
        'mother_name',
        'mother_occupation',
        'mother_contact_number',
        'mother_monthly_income',
        'father_name',
        'father_occupation',
        'father_contact_number',
        'father_monthly_income',
        'guardian_name',
        'guardian_occupation',
        'guardian_contact_number',
        'guardian_monthly_income',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
