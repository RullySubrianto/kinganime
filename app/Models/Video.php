<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            $video->slug = uniqid();
        });
    }

    // Route model binding
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_video', 'video_id', 'user_id')->withTimestamps();
    }

    public function savedByUsersToWatchlist()
    {
        return $this->belongsToMany(User::class, 'user_video_watchlist', 'video_id', 'user_id')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_video', 'video_id', 'category_id')->withTimestamps();
    }

}
