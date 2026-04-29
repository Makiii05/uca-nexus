<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        "code",
        "description",
        "status",
        "department_id",
        "enrollment_type",
    ];

    public function curricula(){
        return $this->hasMany(Curriculum::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

    public function levels(){
        return $this->hasMany(Level::class);
    }
}
