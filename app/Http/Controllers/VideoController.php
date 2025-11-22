<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    use AuthorizesRequests;

    public function home()
    {
        $videos = Video::where('status', 'published')
            ->latest()
            ->limit(6)
            ->get();

        // dd($videos);

        return view('home', [
            'videos' => $videos,
        ]);
    }

    public function postVideo()
    {
        // Categories
        $categories = Category::oldest()->get(['id', 'name']);
        
        return view('profile.post-video', [
            'categories' => $categories,
        ]);
    }

    public function storeVideo(Request $request)
    {
        // Validate Input
        $video = $request->validate([
            'title' => 'required|string',
            'external_link' => 'required|string', 
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,webp|max:20480',
            'status' => 'required|in:published,draft',
        ]);
        
        // Validate Category
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        // Create Record
        $video = new Video($video);

        // Associate User
        $video->user()->associate(Auth::user());
        
        // Handle Thubmnail
        if ($request->hasFile('thumbnail')) {
            $path = ImageHelper::optimizeAndStore($request->file('thumbnail'), 'uploads/thumbnail');
            
            // Add Thumbnail Record
            $video->thumbnail = $path;
            
            // Save Model
            $video->save();
        }

        // Update Category in Pivot Table
        $video->categories()->sync($request->categories);

        // Return Back
        return back()->with('success','Video Berhasil Dipost.');
    }

    public function show(Video $video)
    {
        // Authorize
        $this->authorize('view', $video);

        // Increase Views Count
        $video->views_count += 1;
        $video->save();

        // Other Videos
        $otherVideos = Video::where('status', 'published')
            ->orWhere('id', '!=', $video->id)
            ->latest()
            ->limit(6)
            ->get();
        
        // Return Page
        return view('video', [
            'video' => $video,
            'otherVideos' => $otherVideos,
        ]);
    }

    public function saveVideo(Video $video)
    {
        // Authorize
        $this->authorize('save', $video);
        
        // User Data
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Save House
        $user->savedVideo()->toggle($video->id);
        
        // Flash Message
        $saved = $user->savedVideo->contains($video->id);
        
        $flashMessage = $saved 
            ? 'Video berhasil disimpan'
            : 'Video batal disimpan';

        // Return Back
        return back()->with('success', $flashMessage);
    }

    public function saveVideoToWatchlist(Video $video)
    {
        // Authorize
        $this->authorize('save', $video);
        
        // User Data
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Save House
        $user->savedVideoToWatchlist()->toggle($video->id);
        
        // Flash Message
        $saved = $user->savedVideoToWatchlist->contains($video->id);
        
        $flashMessage = $saved 
            ? 'Video berhasil disimpan ke wacthlist'
            : 'Video batal disimpan ke watchlist';

        // Return Back
        return back()->with('success', $flashMessage);
    }
}
