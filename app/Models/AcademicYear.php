<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'code',
        'description',
        'start_year',
        'end_year',
        'status',
    ];

    protected $appends = [
        'label',
    ];

    public function getLabelAttribute(): string
    {
        $description = trim((string) $this->description);

        if ($description !== '') {
            return $description;
        }

        $startYear = trim((string) $this->start_year);
        $endYear = trim((string) $this->end_year);

        if ($startYear !== '' && $endYear !== '') {
            return $startYear . ' - ' . $endYear;
        }

        return trim((string) $this->code);
    }
}