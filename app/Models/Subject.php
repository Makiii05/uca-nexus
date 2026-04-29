<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        "code",
        "description",
        "unit",
        "lech",
        "labh",
        "lecu",
        "labu",
        "type",
        "education_level",
        "weight_category",
        "status",
    ];

    public function prospectuses(){
        return $this->hasMany(Prospectus::class);
    }
}
