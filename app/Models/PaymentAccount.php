<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    protected $fillable = ['description'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'description_id');
    }
}
