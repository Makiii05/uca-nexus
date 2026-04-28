<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssessmentHistory extends Model
{
    protected $fillable = [
        'student_id',
        'academic_term_id',
        'date_printed',
    ];

    protected $casts = [
        'date_printed' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicTerm(): BelongsTo
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function enlistments(): HasMany
    {
        return $this->hasMany(AssessmentHistoryEnlistment::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(AssessmentHistoryFee::class);
    }

    public function studentInfo(): HasOne
    {
        return $this->hasOne(AssessmentHistoryStudent::class);
    }
}
