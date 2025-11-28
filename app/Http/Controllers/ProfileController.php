<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\BlacklistedEmail;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Personal Information
    public function index()
    {
        return view('profile.index');
    }

    // Update Personal Information
    public function updateProfile(Request $request)
    {
        // Validate Input
        $request->validate([
            'name' => 'required|string',
        ]);

        // User Data
        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        
        // Update Data
        $user->update([
            'name' => $request->name,
        ]);
        $user->save();

        // Redirect Back
        return back()->with('success', 'Informasi Pribadi Berhasil Diubah.');
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        // Validate Input
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        // User Data
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check Old Password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Return Back
        return back()->with('success', 'Password berhasil diubah.');
    }

    // Control Users
    public function controlUsers()
    {
        return view('profile.control-users');
    }

    public function unblockControlUsers(User $user)
    {
        // Delete User Email in Blacklisted Email
        BlacklistedEmail::where('email', $user->email)->delete();

        return back()->with('success', "User batal diblokir.");
    }

    public function blockControlUsers(User $user)
    {
        // Add User Email to Blacklisted Email
        BlacklistedEmail::firstOrCreate([
            'email' => $user->email,
        ]);

        return back()->with('success', "User berhasil diblokir.");
    }

    // Destroy Users
    public function destroyControlUsers(User $user)
    {
        // Delete user
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
 
    // All Video
    public function allVideo()
    {
        return view('profile.all-video');
    }

    // Edit Video in All Video
    public function editVideoAllVideo(Video $video)
    {
        // Categories
        $categories = Category::oldest()->get(['id', 'name']);

        return view('profile.edit-video-all-video', [
            'video' => $video,
            'categories' => $categories,
        ]);
    }

    // Update Video in All Video
    public function updateVideoAllVideo(Request $request, Video $video)
    {
        // Validate Input
        $validated = $request->validate([
            'title' => 'required|string',
            'external_link' => 'required|string', 
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480|dimensions:ratio=25/14',
            'status' => 'required|in:published,draft',
        ]);
        
        // Validate Category
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        // Old Thumbnail
        $oldThumbnail = str_replace('storage/', '', $video->thumbnail);

        // Update post
        $video->update($validated);

        // Handle Thubmnail
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            $oldThumbnail && Storage::disk('public')->delete($oldThumbnail);
            
            $path = ImageHelper::optimizeAndStore($request->file('thumbnail'), 'uploads/thumbnail');
            
            // Add Thumbnail Record
            $video->thumbnail = $path;
            
            // Save Model
            $video->save();
        } else {
            $video->thumbnail = $oldThumbnail;
            $video->save();
        }

        // Update Category in Pivot Table
        $video->categories()->sync($request->categories);
        
        // Redirect to all jobs
        return redirect()->route('all-video.index')->with('success', 'Video berhasil diubah.');
    }

    // Destroy Video in All Video
    public function destroyVideoAllVideo(Video $video)
    {
        // Delete Thumbnail
        $video->company_logo && Storage::disk('public')->delete('uploads/thumbnail/' . $video->thumbnail);

        // Delete Video
        $video->delete();

        // Return
        return back()->with('success', 'Video berhasil dihapus.');
    }

    // Category
    public function indexCategory()
    {
        return view('profile.category');
    }

    public function destroyCategory(Category $category)
    {
        // Delete Category
        $category->delete();

        // Return
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    public function createCategory()
    {
        // Videos
        $videos = Video::latest()->get(['id', 'title']);

        // Return
        return view('profile.add-category', [
            'videos' => $videos,
        ]);
    }

    public function storeCategory(Request $request)
    {
        // Validate Input
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name',
        ]);
        
        // Validate Category
        $request->validate([
            'videos' => 'required|array',
            'videos.*' => 'integer|exists:videos,id',
        ]);

        // Create Category
        $category = Category::create($validated);

        // Update Category in Pivot Table
        $category->videos()->sync($request->videos);

        // Return Back
        return back()->with('success','Kategori baru berhasil dibuat.');
    }

    // Saved Video
    public function savedVideo()
    {
        // User Data
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Saved Videos
        $videos = $user->savedVideo()
            ->orderByPivot('created_at', 'desc')
            ->paginate(12);

        return view('profile.saved-video', [
            'user' => $user,
            'videos' => $videos
        ]);
    }

    // History
    public function history()
    {
        // User Data
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $videos = $user->history()->paginate(12);

            return view('profile.history', [
                'videos' => $videos,
            ]);
        }

        return view('profile.history', [
            'videos' => null
        ]);
    }

    // History for Guest
    public function historyGuest(Request $request)
    {
        // Step 1: numeric filter (safe)
        $ids = collect($request->ids)->filter(fn($id) => is_numeric($id))->map(fn($id) => (int)$id)->toArray();

        // Step 2: get only existing published videos
        $existingIds = Video::whereIn('id', $ids)
            ->where('status', 'published')
            ->pluck('id')
            ->toArray();

        if (empty($existingIds)) {
            return view('profile.history', ['videos' => null]);
        }

        // Step 3: preserve the original order
        $orderedIds = array_values(array_filter($ids, fn($id) => in_array($id, $existingIds)));

        $videos = Video::whereIn('id', $orderedIds)
            ->orderByRaw("FIELD(id, " . implode(',', $orderedIds) . ")")
            ->paginate(12, ['id', 'title', 'thumbnail', 'views_count']);

        return view('profile.history', [
            'videos' => $videos,
        ]);
    }
}
