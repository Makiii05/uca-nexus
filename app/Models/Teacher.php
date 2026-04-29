<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    protected $fillable = [
        'code',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'status',
    ];

    public function account(): HasOne
    {
        return $this->hasOne(TeacherAccount::class);
    }

    public function offerings(): HasMany
    {
        return $this->hasMany(TeacherOffering::class);
    }

    public function gradeColumns(): HasMany
    {
        return $this->hasMany(GradeColumn::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
