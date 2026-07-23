<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boost extends Model
{
    protected $fillable = ['user_id', 'starts_at', 'ends_at', 'impressions', 'is_active'];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
