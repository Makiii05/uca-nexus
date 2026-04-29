<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeColumn extends Model
{
    protected $fillable = [
        'teacher_id',
        'offering_id',
        'academic_term_id',
        'grading_period',
        'component_type',
        'column_number',
        'highest_possible_score',
        'description',
    ];

    protected $casts = [
        'column_number' => 'integer',
        'highest_possible_score' => 'decimal:2',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function offering(): BelongsTo
    {
        return $this->belongsTo(SubjectOffering::class, 'offering_id');
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function rawScores(): HasMany
    {
        return $this->hasMany(RawScore::class, 'column_id');
    }
}
