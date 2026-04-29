<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'teacher_id',
        'term_id',
        'grading_period',
        'ww_whole',
        'pt_whole',
        'qa_whole',
        'ww_total',
        'pt_total',
        'qa_total',
        'ww_percent',
        'pt_percent',
        'qa_percent',
        'initial_grade',
        'period_grade',
        'status',
        'is_direct_input',
        'submtted_at',
        'approved_by',
        'approved_at',
        'fnalized_at',
        'remarks',
    ];

    protected $casts = [
        'ww_whole' => 'decimal:2',
        'pt_whole' => 'decimal:2',
        'qa_whole' => 'decimal:2',
        'ww_total' => 'decimal:2',
        'pt_total' => 'decimal:2',
        'qa_total' => 'decimal:2',
        'ww_percent' => 'decimal:2',
        'pt_percent' => 'decimal:2',
        'qa_percent' => 'decimal:2',
        'initial_grade' => 'decimal:2',
        'period_grade' => 'decimal:2',
        'is_direct_input' => 'boolean',
        'submtted_at' => 'datetime',
        'approved_by' => 'datetime',
        'approved_at' => 'datetime',
        'fnalized_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }
}
