<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        "code",
        "description",
        "education_level",
        "status",
    ];

    public function programs(){
        return $this->hasMany(Program::class);
    }

    public function components(){
        return $this->hasMany(Component::class);
    }

    public function gradingSystems(){
        return $this->hasMany(GradingSystem::class);
    }
}
