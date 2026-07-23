<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperLike extends Model
{
    protected $fillable = ['user_id', 'target_user_id', 'used_at'];

    protected function casts(): array
    {
        return ['used_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
