<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'occupation', 'education',
        'relationship_goal', 'looking_for',
        'min_age_preference', 'max_age_preference', 'max_distance_km',
        'is_verified', 'is_premium', 'is_boosted', 'boosted_until',
        'profile_completion',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'is_premium' => 'boolean',
            'is_boosted' => 'boolean',
            'boosted_until' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
