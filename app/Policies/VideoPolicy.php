<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{
    // Determine whether the user own the house.
    // public function ownTheHouse(User $user, Video $video): bool
    // {
    //     // Must Own The Video
    //     return $user->houses()
    //         ->where('id', $video->id)
    //         ->exists();
    // }

    // Determine whether the user can store house.
    // public function create(User $user): bool
    // {
    //     // Must be an admin Or seller that have post_quota
    //     return $user->role === 'admin' || ($user->role === 'seller' && $user->seller->post_quota > 0); 
    // }

    // Determine whether the user can view the video.
    public function view(?User $user, Video $video): bool
    {
        // dd($video);
        
        // Anyone could see published video
        if ($video->status === 'published') {
            return true;
        }

        // Only admin can see unpublished video
        if ($user && $user->role === 'admin') {
            return true;
        }

        // Deny everyone else
        return false;
    }

    // Determine whether the user can save the house.
    public function save(?User $user, Video $video): bool
    {
        // Anyone could save published video
        if ($video->status === 'published') {
            return true;
        }

        // Must authenticated to save unpublished video
        if (!$user) {
            return false;
        }

        // Allow for admin
        if ($user->role === 'admin') {
            return true;
        }

        // Deny everyone else
        return false;
    }
}
