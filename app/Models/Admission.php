<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'applicant_id',
        'interview_schedule_id',
        'interview_score',
        'interview_remark',
        'interview_result',
        'exam_schedule_id',
        'math_score',
        'science_score',
        'english_score',
        'filipino_score',
        'abstract_score',
        'exam_score',
        'exam_result',
        'final_score',
        'decision',
        'program_id',
        'evaluation_schedule_id',
        'evaluated_by',
        'evaluated_at',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function interviewSchedule()
    {
        return $this->belongsTo(Schedule::class, 'interview_schedule_id');
    }

    public function examSchedule()
    {
        return $this->belongsTo(Schedule::class, 'exam_schedule_id');
    }
    
    public function evaluationSchedule()
    {
        return $this->belongsTo(Schedule::class, 'evaluation_schedule_id');
    }
}
