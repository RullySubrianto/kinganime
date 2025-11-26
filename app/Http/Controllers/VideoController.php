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

    // Homepage
    public function home()
    {
        // Videos
        $videos = Video::where('status', 'published')
            ->latest()
            ->paginate(12, ['id', 'title', 'thumbnail', 'views_count'])
            ->onEachSide(1);

        // Categrories
        $categories = Category::latest()->get(['id', 'name']);

        return view('home', [
            'videos' => $videos,
            'categories' => $categories,
        ]);
    }

    // Search Video
    public function searchVideo(Request $request)
    {
        // Validate Input
        $request->validate([
            'search' => 'nullable|string',
            'orderBy' => 'nullable|in:desc,asc',
        ]);

        // Query Builder
        $videos = Video::query();

        // Search
        $videos->when($request->search, function ($query) use($request) {
            return $query->where('title', 'like', '%' . $request->search . '%');
        });

        // Order By
        $videos->when($request->orderBy, function ($query) use($request) {
            return $query->orderBy('created_at', $request->orderBy);
        });

        // Run the query and get results
        $videos = $videos->latest()
            ->paginate(12, ['id', 'title', 'thumbnail', 'views_count'])
            ->appends($request->query())
            ->onEachSide(1);

        // Return
        return view('search', [
            'videos' => $videos,
            'search' => $request->search,
            'orderBy' => $request->orderBy,
        ]);
    }

    // Post Video in Profile
    public function postVideo()
    {
        // Categories
        $categories = Category::oldest()->get(['id', 'name']);
        
        return view('profile.post-video', [
            'categories' => $categories,
        ]);
    }

    // Store Video
    public function storeVideo(Request $request)
    {
        // Validate Input
        $video = $request->validate([
            'title' => 'required|string',
            'external_link' => 'required|string', 
            'thumbnail' => 'required|image|mimes:jpeg,jpg,png,webp|max:20480|dimensions:ratio=25/14',
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

    // Dedicated Video
    public function show(Video $video)
    {
        // Authorize
        $this->authorize('view', $video);

        // Increase Views Count safely
        $video->increment('views_count');

        // User Data
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Update User History
        if ($user) {
            $user->history()->syncWithoutDetaching([
                $video->id => ['updated_at' => now()]
            ]);
        }

        // Video
        $video = Video::select('id', 'title', 'thumbnail', 'external_link', 'views_count')
            ->findOrFail($video->id);

        // Other Videos
        $otherVideos = Video::where('status', 'published')
            ->where('id', '!=', $video->id)
            ->latest()
            ->limit(12)
            ->get(['id', 'title', 'thumbnail', 'views_count']);
        
        // Return Page
        return view('video', [
            'video' => $video,
            'otherVideos' => $otherVideos,
        ]);
    }

    // Category
    public function category(Category $category)
    {
        // Videos
        $videos = $category->videos()
            ->where('status', 'published')
            ->orderBy('videos.created_at', 'desc')
            ->paginate(12, ['videos.id', 'videos.title', 'videos.thumbnail', 'videos.views_count'])
            ->onEachSide(1);

        return view('category', [
            'category' => $category,
            'videos' => $videos,
        ]);
    }

    // Save Video
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

    // Save to Watchlist
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
