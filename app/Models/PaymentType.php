<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $fillable = ['description'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'type_id');
    }
}
