<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enums\TransactionStatus;

class Transaction extends Model
{
    protected $fillable = [
        'referenceId',
        'name',
        'email',
        'amount',
        'status',
        'verified_at',
        'remarks',
    ];

    protected $casts = [
        'remarks'     => 'array',
        'verified_at' => 'datetime',
        'status'      => TransactionStatus::class,
    ];
}
