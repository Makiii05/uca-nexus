<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentHistoryEnlistment extends Model
{
    protected $fillable = [
        'assessment_history_id',
        'code',
        'description',
        'units',
    ];

    protected $casts = [
        'units' => 'decimal:2',
    ];

    public function assessmentHistory(): BelongsTo
    {
        return $this->belongsTo(AssessmentHistory::class);
    }
}
