<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\UserPreference;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class AllVideo extends Component
{
    use WithPagination;

    // Search bar
    #[Validate('nullable|string')]
    public $searchVideo = '';

    // Per Page
    #[Validate('nullable|integer')]
    public $page = 10;

    // Acite Date Filters
    #[Validate('nullable|date')]
    public $createdFrom = '';

    #[Validate('nullable|date')]
    public $createdUntil = '';

    // Select   
    #[Validate('nullable|array')]
    public $bulkSelected = [];

    // Update Column Preference Property
    #[Validate('required|boolean')]
    public $colThumbnail =  false;
    
    #[Validate('required|boolean')]
    public $colExternalLink =  false;

    #[Validate('required|boolean')]
    public $colStatus = false;

    #[Validate('required|boolean')]
    public $colViewsCount = false;

    // Initialilzing
    public function mount()
    {
        // Validate property
        $this->validate();
        
        // User data
        $user = Auth::user();

        // Col Pref
        $colPref = $user->userPreferences
            ->where('key', 'table_column_all_video')
            ->first();

        // Update Checkbox
        if ($colPref) {
            $this->colThumbnail = $colPref->value['colThumbnail'];
            $this->colExternalLink = $colPref->value['colExternalLink'];
            $this->colStatus = $colPref->value['colStatus'];
            $this->colViewsCount = $colPref->value['colViewsCount'];
        }

    }

    // Update Column Preference Form
    public function saveColPref()
    {
        // User data
        $user = Auth::user();

        // Validate input
        $validated = $this->validate([
            'colThumbnail' => 'required|boolean',
            'colExternalLink' => 'required|boolean',
            'colStatus' => 'required|boolean',
            'colViewsCount' => 'required|boolean',
        ]);

        // User preference
        $pref = UserPreference::where('user_id', '=', $user->id)
            ->where('key', 'table_column_all_video')
            ->first();
        
        if ($pref) {
            // Update record
            $pref->update([
                'value' => $validated
            ]);
            
        } else {
            // Create record
            UserPreference::create([
                'user_id' => $user->id,
                'key' => 'table_column_all_video',
                'value' => $validated
            ]);
        }
    }

    // Reset Column Preference Button
    public function resetColPref()
    {
        // Validate property
        $this->validate();
        
        // User data
        $user = Auth::user();

        // User preference
        $pref = UserPreference::where('user_id', '=', $user->id)
            ->where('key', 'table_column_all_video')
            ->first();
        
        if ($pref) {
            // Delete record
            $pref->delete();
        }

        // Update Checkbox
        $this->colThumbnail = false;
        $this->colExternalLink = false;
        $this->colStatus = false;
        $this->colViewsCount = false;
    }

    // Pagination view
    // public function paginationView()
    // {
    //     return 'vendor.livewire.custom-pagination-for-all-jobs';
    // }

    // Reset Pagination
    public function updating($name, $value)
    {
        $this->resetPage();
    }

    // Reset Filters
    public function resetFliters($filter)
    {
        $this->reset($filter);
    }

    // Reset Bulk Selected
    public function updatedCreatedFrom()
    {
        $this->bulkSelected = [];
    }

    // Reset Bulk Selected
    public function updatedCreatedUntil()
    {
        $this->bulkSelected = [];
    }

    // Deselect all button
    public function deselectAll()
    {
        $this->bulkSelected = [];
    }

    // Delete Bulk Selected Button
    public function deleteSelected()
    {
        // Validate property
        $this->validate();

        // User data
        $user = Auth::user();
        
        // Video data
        $videoToDelete = Video::whereIn('id', $this->bulkSelected)
            ->pluck('id');

        if ($videoToDelete->isNotEmpty() && $user->role === 'admin') {
            // Delete Video
            Video::destroy($videoToDelete);
        }

        // Reset selected video
        $this->bulkSelected = [];

        session()->flash('success', 'Video berhasil dihapus.');
    }


    public function render()
    {
        // Validate property
        $this->validate();

        // User data
        $user = Auth::user();

        // Query builder
        $videos = Video::query();

        // Eager Load
        // $video->with('user.companyUsers');

        // Search Video
        $videos->when($this->searchVideo, function ($query) {
            return $query->where('title', 'like', '%' . $this->searchVideo . '%');
        });

        // Created From
        $videos->when($this->createdFrom, function ($query) {
            return $query->where('created_at', '>=', Carbon::parse($this->createdFrom));
        });

        // Created Until
        $videos->when($this->createdUntil, function ($query) {
            return $query->where('created_at', '<=', Carbon::parse($this->createdUntil)->endOfDay());
        });

        // Run the query and get results
        $videos = $videos->latest()
            ->paginate($this->page)
            ->onEachSide(1);

        // Column Preference Data
        $colPref = $user->userPreferences
            ->where('key', 'table_column_all_video')
            ->first();

        return view('livewire.all-video', [
            'videos' => $videos,
            'user' => $user,
            'colPref' => $colPref
        ]);
    }
}