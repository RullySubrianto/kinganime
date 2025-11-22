<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Relationship
    public function categories()
    {
        return $this->belongsToMany(Video::class, 'category_video', 'category_id', 'video_id')->withTimestamps();
    }
}
