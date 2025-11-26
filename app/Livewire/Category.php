<?php

namespace App\Livewire;

use App\Models\Category as ModelsCategory;
use App\Models\UserPreference;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class Category extends Component
{
    use WithPagination;

    // Search bar
    #[Validate('nullable|string')]
    public $searchCategory = '';

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
    public $colTotalVideo =  false;
    
    #[Validate('required|boolean')]
    public $colVideos =  false;

    #[Validate('required|boolean')]
    public $colCreatedAt  = false;
    
    // Initialilzing
    public function mount()
    {
        // Validate property
        $this->validate();
        
        // User data
        $user = Auth::user();

        // Validate Admin
        if ($user->role !== 'admin') abort(404);

        // Col Pref
        $colPref = $user->userPreferences
            ->where('key', 'table_column_category')
            ->first();

        // Update Checkbox
        if ($colPref) {
            $this->colTotalVideo = $colPref->value['colTotalVideo'];
            $this->colVideos = $colPref->value['colVideos'];
            $this->colCreatedAt = $colPref->value['colCreatedAt'];
        }

    }

    // Update Column Preference Form
    public function saveColPref()
    {
        // User data
        $user = Auth::user();

        // Validate Admin
        if ($user->role !== 'admin') abort(404);

        // Validate input
        $validated = $this->validate([
            'colTotalVideo' => 'required|boolean',
            'colVideos' => 'required|boolean',
            'colCreatedAt' => 'required|boolean',
        ]);

        // User preference
        $pref = UserPreference::where('user_id', '=', $user->id)
            ->where('key', 'table_column_category')
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
                'key' => 'table_column_category',
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

        // Validate Admin
        if ($user->role !== 'admin') abort(404);

        // User preference
        $pref = UserPreference::where('user_id', '=', $user->id)
            ->where('key', 'table_column_category')
            ->first();
        
        if ($pref) {
            // Delete record
            $pref->delete();
        }

        // Update Checkbox
        $this->colTotalVideo = false;
        $this->colVideos = false;
        $this->colCreatedAt = false;
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

        // Validate Admin
        if ($user->role !== 'admin') abort(404);
        
        // Video data
        $categoriesToDelete = ModelsCategory::whereIn('id', $this->bulkSelected)
            ->pluck('id');

        if ($categoriesToDelete->isNotEmpty() && $user->role === 'admin') {
            // Delete Video
            ModelsCategory::destroy($categoriesToDelete);
        }

        // Reset selected video
        $this->bulkSelected = [];

        session()->flash('success', 'Kategori berhasil dihapus.');
    }


    public function render()
    {
        // Validate property
        $this->validate();

        // User data
        $user = Auth::user();

        // Validate Admin
        if ($user->role !== 'admin') abort(404);

        // Query builder
        $categories = ModelsCategory::query();

        // Eager Load
        $categories->with('videos');

        // Search Video
        $categories->when($this->searchCategory, function ($query) {
            return $query->where('name', 'like', '%' . $this->searchCategory . '%');
        });

        // Created From
        $categories->when($this->createdFrom, function ($query) {
            return $query->where('created_at', '>=', Carbon::parse($this->createdFrom));
        });

        // Created Until
        $categories->when($this->createdUntil, function ($query) {
            return $query->where('created_at', '<=', Carbon::parse($this->createdUntil)->endOfDay());
        });

        // Run the query and get results
        $categories = $categories->latest()
            ->paginate($this->page)
            ->onEachSide(1);

        // Column Preference Data
        $colPref = $user->userPreferences
            ->where('key', 'table_column_category')
            ->first();

        return view('livewire.category', [
            'categories' => $categories,
            'user' => $user,
            'colPref' => $colPref
        ]);
    }
}