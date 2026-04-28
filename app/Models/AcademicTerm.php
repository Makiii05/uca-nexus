<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    protected $fillable = [
        "code",
        "description",
        "type",
        "department_id",
        "academic_year",
        "start_date",
        "end_date",
        "status",
    ];

    public function prospectuses()
    {
        return $this->hasMany(Prospectus::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
