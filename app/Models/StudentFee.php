<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $fillable = [
        "student_id",
        "fee_id",
        "academic_term_id",
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function fee(){
        return $this->belongsTo(Fee::class);
    }

    public function academicTerm(){
        return $this->belongsTo(AcademicTerm::class);
    }
}
