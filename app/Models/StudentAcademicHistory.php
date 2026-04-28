<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// StudentAcademicHistory Model
class StudentAcademicHistory extends Model
{
    protected $fillable = [
        'student_id',
        'elementary_school_name',
        'elementary_school_address',
        'elementary_inclusive_years',
        'junior_school_name',
        'junior_school_address',
        'junior_inclusive_years',
        'senior_school_name',
        'senior_school_address',
        'senior_inclusive_years',
        'college_school_name',
        'college_school_address',
        'college_inclusive_years',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
