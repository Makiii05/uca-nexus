<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Component extends Model
{
    protected $fillable = [
        'code',
        'description',
        'percentage',
        'department_id',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function gradingSystems(): BelongsToMany
    {
        return $this->belongsToMany(GradingSystem::class, 'grading_components', 'component_id', 'grading_id');
    }
}
