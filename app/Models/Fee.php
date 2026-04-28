<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable = [
        "description",
        "amount",
        "type",
        "month_to_pay",
        "group",
        "academic_term_id",
        "program_id",
        "student_id",
    ];

    public function program(){
        return $this->belongsTo(Program::class);
    }

    public function academicTerm(){
        return $this->belongsTo(AcademicTerm::class);
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function studentFees(){
        return $this->hasMany(StudentFee::class);
    }
}
