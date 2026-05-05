<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// Student Model
class Student extends Model
{
    protected $fillable = [
        'student_number',
        'lrn',
        'department_id',
        'program_id',
        'level_id',
        'last_name',
        'first_name',
        'middle_name',
        'sex',
        'citizenship',
        'religion',
        'birthdate',
        'place_of_birth',
        'civil_status',
        'application_id',
        'status',
        'student_type',
        'is_exported',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'is_exported' => 'boolean',
    ];

    // Relationships
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'application_id');
    }

    public function contact(): HasOne
    {
        return $this->hasOne(StudentContact::class);
    }

    public function guardian(): HasOne
    {
        return $this->hasOne(StudentGuardian::class);
    }

    public function academicHistory(): HasOne
    {
        return $this->hasOne(StudentAcademicHistory::class);
    }

    public function assessmentHistories(): HasMany
    {
        return $this->hasMany(AssessmentHistory::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function account(): HasOne
    {
        return $this->hasOne(StudentAccount::class);
    }
}
