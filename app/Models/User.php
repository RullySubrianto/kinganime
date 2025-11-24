<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function savedVideo()
    {
        return $this->belongsToMany(Video::class, 'user_video', 'user_id', 'video_id')->withTimestamps();
    }

    public function savedVideoToWatchlist()
    {
        return $this->belongsToMany(Video::class, 'user_video_watchlist', 'user_id', 'video_id')->withTimestamps();
    }

    public function userPreferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    public function history()
    {
        return $this->belongsToMany(Video::class, 'video_user_histories')
            ->withTimestamps()
            ->orderByPivot('updated_at', 'desc');
    }
}
