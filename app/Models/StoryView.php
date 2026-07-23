<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryView extends Model
{
    protected $fillable = ['story_id', 'viewer_id', 'viewed_at'];

    protected function casts(): array
    {
        return ['viewed_at' => 'datetime'];
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function viewer()
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }
}
