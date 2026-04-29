<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawScore extends Model
{
    protected $fillable = [
        'student_id',
        'column_id',
        'score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function gradeColumn(): BelongsTo
    {
        return $this->belongsTo(GradeColumn::class, 'column_id');
    }
}
