<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PremiumSubscription extends Model
{
    protected $fillable = [
        'user_id', 'plan', 'amount', 'currency',
        'starts_at', 'ends_at', 'status', 'auto_renew',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'auto_renew' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
