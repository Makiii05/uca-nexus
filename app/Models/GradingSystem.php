<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradingSystem extends Model
{
    protected $fillable = [
        'description',
        'total_percentage',
        'department_id',
    ];

    protected $casts = [
        'total_percentage' => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class, 'grading_components', 'grading_id', 'component_id');
    }

    public function subjectOfferings(): HasMany
    {
        return $this->hasMany(SubjectOffering::class, 'grading_id');
    }
}
