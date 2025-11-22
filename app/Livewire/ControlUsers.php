<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UserPreference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class ControlUsers extends Component
{
    use WithPagination;

    // Search bar
    #[Validate('nullable|string')]
    public $searchUser = '';

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
    public $colEmail =  false;

    #[Validate('required|boolean')]
    public $colCreatedAt =  false;

    // Initialilzing
    public function mount()
    {
        // Validate property
        $this->validate();
        
        // User data
        $user = Auth::user();

        // Col Pref
        $colPref = $user->userPreferences
            ->where('key', 'table_column_control_users')
            ->first();

        // Update Checkbox
        if ($colPref) {
            $this->colEmail = $colPref->value['colEmail'];
            $this->colCreatedAt = $colPref->value['colCreatedAt'];
        }

    }

    // Update Column Preference Form
    public function saveColPref()
    {
        // User data
        $user = Auth::user();

        // Validate input
        $validated = $this->validate([
            'colEmail' => 'required|boolean',
            'colCreatedAt' => 'required|boolean',
        ]);

        // User preference
        $pref = UserPreference::where('user_id', '=', $user->id)
            ->where('key', 'table_column_control_users')
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
                'key' => 'table_column_control_users',
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
            ->where('key', 'table_column_control_users')
            ->first();
        
        if ($pref) {
            // Delete record
            $pref->delete();
        }

        // Update Checkbox
        $this->colEmail = false;
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
        
        // Users in bulk selected
        $usersToDelete = User::whereIn('id', $this->bulkSelected)
            ->pluck('id');

        if ($usersToDelete->isNotEmpty() && $user->id === 1) {
            // Delete user
            User::destroy($usersToDelete);
        }

        // Reset selected user
        $this->bulkSelected = [];

        session()->flash('success', 'User berhasil dihapus.');
    }

    public function render()
    {
        // Validate property
        $this->validate();

        // User data
        $user = Auth::user();

        // Query builder
        $users = User::query();

        // Search User
        $users->when($this->searchUser, function ($query) {
            return $query->where('name', 'like', '%' . $this->searchUser . '%')
                            ->where('id', '!=', '1')
                            ->orWhere('email', 'like', '%' . $this->searchUser . '%');
        });

        // Created From
        $users->when($this->createdFrom, function ($query) {
            return $query->where('created_at', '>=', Carbon::parse($this->createdFrom));
        });

        // Created Until
        $users->when($this->createdUntil, function ($query) {
            return $query->where('created_at', '<=', Carbon::parse($this->createdUntil)->endOfDay());
        });

        // Run the query and get results
        $users = $users->where('id', '!=', '1')
            ->latest()
            ->paginate($this->page)
            ->onEachSide(1);

        // Column Preference Data
        $colPref = $user->userPreferences
            ->where('key', 'table_column_control_users')
            ->first();

        return view('livewire.control-users', [
            'user' => $user,
            'users' => $users,
            'colPref' => $colPref
        ]);
    }
}