<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'payment_reference', 'selcom_transaction_id',
        'purpose', 'amount', 'currency', 'method', 'status', 'gateway_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
