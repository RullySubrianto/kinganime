<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

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

    public function controlUsers()
    {
        return view('profile.control-users');
    }

    // Destroy Users
    public function destroyControlUsers(User $user)
    {
        // Delete user
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
 
    public function allVideo()
    {
        return view('profile.all-video');
    }

    public function editVideoAllVideo(Video $video)
    {
        // Categories
        $categories = Category::oldest()->get(['id', 'name']);

        return view('profile.edit-video-all-video', [
            'video' => $video,
            'categories' => $categories,
        ]);
    }

    public function updateVideoAllVideo(Request $request, Video $video)
    {
        // dd($request->all());
        // Validate Input
        $validated = $request->validate([
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
        }

        // Update Category in Pivot Table
        $video->categories()->sync($request->categories);
        
        // Redirect to all jobs
        return redirect()->route('all-video.index')->with('success', 'Video berhasil diubah.');
    }

    public function destroyVideoAllVideo(Video $video)
    {
        // Delete thumbnail
        $video->company_logo && Storage::disk('public')->delete('uploads/thumbnail/' . $video->thumbnail);

        // Delete video
        $video->delete();

        // Return
        return back()->with('success', 'Video berhasil dihapus.');
    }
}
