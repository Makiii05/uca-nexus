<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $fillable = [
        "curriculum",
        "status",
        "department_id",
    ];

    public function prospectuses(){
        return $this->hasMany(Prospectus::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
}
