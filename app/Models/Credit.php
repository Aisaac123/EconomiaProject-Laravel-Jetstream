<?php

namespace App\Models;

use App\Enums\CalculationType;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = [
        'user_id',
        'debtor_names',
        'debtor_last_names',
        'debtor_id_number',
        'type',
        'inputs',
        'results',
        'status',
        'reference_code',
        'calculated_at',
    ];

    protected $casts = [
        'inputs' => 'array',
        'results' => 'array',
        'calculated_at' => 'datetime',
        'type' => CalculationType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

