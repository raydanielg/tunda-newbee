<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePhoto extends Model
{
    protected $fillable = ['user_id', 'path', 'position', 'is_primary'];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
