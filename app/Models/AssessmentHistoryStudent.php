<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentHistoryStudent extends Model
{
    protected $fillable = [
        'assessment_history_id',
        'student_number',
        'name',
        'year_level',
        'program',
        'department',
    ];

    public function assessmentHistory(): BelongsTo
    {
        return $this->belongsTo(AssessmentHistory::class);
    }
}
