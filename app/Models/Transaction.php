<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'student_id',
        'academic_term_id',
        'cashier_id',
        'or_number',
        'description_id',
        'type_id',
        'amount',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function paymentAccount()
    {
        return $this->belongsTo(PaymentAccount::class, 'description_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'type_id');
    }
}
