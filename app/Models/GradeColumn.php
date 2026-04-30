<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeColumn extends Model
{
    protected $table = 'grade_column';

    protected $fillable = [
        'teacher_offering_id',
        'period',
        'component_id',
        'column_number',
        'highest_score',
    ];

    protected $casts = [
        'column_number' => 'integer',
        'highest_score' => 'decimal:2',
    ];

    public function teacherOffering(): BelongsTo
    {
        return $this->belongsTo(TeacherOffering::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function rawScores(): HasMany
    {
        return $this->hasMany(RawScore::class);
    }
}
