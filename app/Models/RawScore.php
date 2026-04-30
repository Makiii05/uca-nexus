<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawScore extends Model
{
    protected $table = 'raw_score';

    protected $fillable = [
        'grade_id',
        'grade_column_id',
        'score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function gradeColumn(): BelongsTo
    {
        return $this->belongsTo(GradeColumn::class);
    }
}
