<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherOffering extends Model
{
    protected $fillable = [
        'teacher_id',
        'offering_id',
        'academic_year_id',
        'status',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function offering(): BelongsTo
    {
        return $this->belongsTo(SubjectOffering::class, 'offering_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
