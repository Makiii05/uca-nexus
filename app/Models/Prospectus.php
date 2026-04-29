<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prospectus extends Model
{
    protected $fillable = [
        "curriculum_id",
        "subject_id",
        "level_id",
        "term_id",
        "status",
    ];

    public function curriculum(){
        return $this->belongsTo(Curriculum::class);
    }

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function level(){
        return $this->belongsTo(Level::class);
    }

    public function term()
    {
        return $this->belongsTo(AcademicTerm::class, 'term_id');
    }
}
