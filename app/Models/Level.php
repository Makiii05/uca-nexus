<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = [
        "code",
        "description",
        "program_id",
        "order",
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    
    public function prospectuses(){
        return $this->hasMany(Prospectus::class);
    }
}
