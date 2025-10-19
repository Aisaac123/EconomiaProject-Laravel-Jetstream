<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'credit_id',
        'amount',
        'principal_paid',
        'interest_paid',
        'remaining_balance',
        'payment_date',
        'metadata',
        'status',
    ];

    protected $casts = [
        'metadata' => 'array',
        'payment_date' => 'date',
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Cálculos dinámicos
    |--------------------------------------------------------------------------
    */

    public function getCapitalProportionAttribute(): float
    {
        return $this->amount > 0
            ? round(($this->principal_paid / $this->amount) * 100, 2)
            : 0;
    }

    public function getInterestProportionAttribute(): float
    {
        return $this->amount > 0
            ? round(($this->interest_paid / $this->amount) * 100, 2)
            : 0;
    }

    public function getIsFinalPaymentAttribute(): bool
    {
        return $this->remaining_balance === 0.0;
    }
}
