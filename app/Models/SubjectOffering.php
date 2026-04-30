<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectOffering extends Model
{
    protected $fillable = [
        'academic_term_id',
        'subject_id',
        'department_id',
        'program_id',
        'level_id',
        'grading_id',
        'code',
        'description',
        'class_size',
    ];

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

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

    public function gradingSystem(): BelongsTo
    {
        return $this->belongsTo(GradingSystem::class, 'grading_id');
    }

    public function enlistments(): HasMany
    {
        return $this->hasMany(Enlistment::class);
    }
}
