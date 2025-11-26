<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    // Relationship
    public function videos()
    {
        return $this->belongsToMany(Video::class, 'category_video', 'category_id', 'video_id')->withTimestamps();
    }
}
