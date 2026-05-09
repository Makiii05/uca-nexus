<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectFee extends Model
{
    protected $fillable = [
        "subject_id",
        "fee_id",
    ];

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function fee(){
        return $this->belongsTo(Fee::class);
    }
}
