<div class="col p-0 overflow-x-auto">
    {{-- Success & Error Flash Message --}}
    {{-- <x-profile.success-and-error-flash-message /> --}}

    {{-- Search Bar, Bulk Actions --}}
    <div class="bg-white border rounded-t-xl py-3 pl-4 pr-3">
        {{-- Bulk Actions, Columns, Filters, Search Bar --}}
        <div class="flex justify-between items-center">
            {{-- Bulk Actions --}}
            @if (count($bulkSelected) > 0)
                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = !open"
                        @click.outside="open = false"
                        class="flex items-center border rounded-lg py-2 px-3 bg-gray-100 hover:bg-gray-200 text-sm gap-2"
                        aria-haspopup="true"
                        :aria-expanded="open ? 'true' : 'false'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-gray-600" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-700">Bulk actions</p>
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        class="absolute left-0 mt-2 bg-white rounded-lg shadow-md border w-52 py-1 z-50"
                        x-cloak>
                        <button
                            type="button"
                            wire:click="deleteSelected"
                            class="w-full text-left text-red-600 flex items-center gap-2 px-3 py-2 text-sm font-medium hover:bg-gray-50 cursor-pointer"
                            style="width: 200px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                            Delete Selected
                        </button>
                    </div>
                </div>
            @else
                <div></div>
            @endif

            {{-- Columns, Filters, Search Bar --}}
            <div class="flex flex-row-reverse items-center">
                <style>[x-cloak]{display:none!important}</style>

                {{-- Columns & Filters --}}
                <div x-data="{ open: null }" class="flex items-center gap-2">
                    {{-- Filters --}}
                    <div class="relative" @click.outside="open = null" @keydown.escape.window="open = null">
                        {{-- Button --}}
                        <button
                            @click.stop="open = open === 'filters' ? null : 'filters'"
                            class="text-gray-500 p-1 hover:text-gray-800 relative"
                            aria-haspopup="true"
                            :aria-expanded="open === 'filters'">

                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-gray-600" viewBox="0 0 16 16">
                                <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
                            </svg>

                            @if ($createdFrom || $createdUntil)
                                <span class="absolute -top-1 -right-1 bg-gray-100 border text-gray-600 rounded text-[11px] px-1 py-[1px]">
                                    {{ $createdFrom && $createdUntil ? '2' : '1' }}
                                </span>
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        <div
                            x-cloak
                            x-show="open === 'filters'"
                            x-transition
                            @click.stop
                            class="absolute right-0 mt-2 bg-white shadow-lg border rounded-lg p-4 w-72 z-50"
                            style="display:none;">
                            
                            <form action="" method="POST" class="space-y-3">
                                @csrf
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold">Filters</span>
                                    <button type="button" wire:click="resetFliters(['createdFrom', 'createdUntil'])" class="text-red-600 text-xs font-semibold hover:underline cursor-pointer">Reset</button>
                                </div>

                                <div class="flex flex-col gap-3 mt-1">
                                    <div>
                                        <label class="block text-xs font-medium">Created from</label>
                                        <input type="date" wire:model="createdFrom" class="mt-1 block w-full border rounded px-3 py-2 text-sm">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium">Created until</label>
                                        <input type="date" wire:model="createdUntil" class="mt-1 block w-full border rounded px-3 py-2 text-sm">
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    @click="open = null; $wire.$refresh()"
                                    class="w-full bg-blue-600 text-white py-2 rounded-md text-sm font-semibold hover:bg-blue-700 cursor-pointer">
                                    Apply filters
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Columns --}}
                    <div class="pl-2 pr-3 relative" @click.outside="open = null" @keydown.escape.window="open = null">
                        {{-- Button --}}
                        <button
                            @click.stop="open = open === 'columns' ? null : 'columns'"
                            class="text-gray-500 p-1 hover:text-gray-800"
                            aria-haspopup="true"
                            :aria-expanded="open === 'columns'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 1 16 16" class="text-gray-600">
                                <g transform="rotate(90 8 8)">
                                    <path fill-rule="evenodd" d="M2.5 12a1.5 1.5 0 0 1 1.5-1.5h10a1.5 1.5 0 0 1 0 3H4a1.5 1.5 0 0 1-1.5-1.5m0-4a1.5 1.5 0 0 1 1.5-1.5h10a1.5 1.5 0 0 1 0 3H4a1.5 1.5 0 0 1-1.5-1.5m0-4a1.5 1.5 0 0 1 1.5-1.5h10a1.5 1.5 0 0 1 0 3H4a1.5 1.5 0 0 1-1.5-1.5"/>
                                </g>
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div
                            x-show="open === 'columns'"
                            x-transition
                            x-cloak
                            @click.stop
                            class="absolute right-0 mt-2 bg-white shadow-lg border rounded-lg p-4 w-72 z-50"
                            style="display:none;">
                            
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-semibold">Columns</p>
                                <button type="button" wire:click="resetColPref" class="text-red-600 text-xs font-semibold hover:underline cursor-pointer">Reset</button>
                            </div>

                            <form wire:submit.prevent="saveColPref" class="mt-3 flex flex-col gap-3">
                                <label class="flex items-center gap-2 text-sm">
                                    <input disabled checked type="checkbox" />
                                    Nama
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input wire:model="colEmail" type="checkbox" class="cursor-pointer"/>
                                    Email
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input wire:model="colCreatedAt" type="checkbox" class="cursor-pointer"/>
                                    Tanggal Dibuat
                                </label>

                                {{-- Apply --}}
                                <button
                                    type="submit"
                                    @click="open = null"
                                    class="mt-2 w-full bg-blue-600 text-white py-2 rounded-md text-sm font-semibold hover:bg-blue-700 cursor-pointer">
                                    Apply columns
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Search Bar --}}
                <div class="px-2 relative">
                    <x-ui.input 
                        type="text"
                        class="py-1.5 ps-4 text-sm rounded-lg shadow-none! border"
                        id="searchUser"
                        name="searchUser"
                        autocomplete="searchUser"
                        placeholder="Cari User..."
                        wire:model.live.debounce.500ms="searchUser"/>
                    <button
                        type="button"
                        class="absolute right-4 top-1 rounded-full p-1.5 bg-primary cursor-pointer">
                            <x-icon name="search" class="h-3.5 w-3.5 text-white"/>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Records Selected --}}
    @if (count($bulkSelected) > 0)
        <div class="bg-transparent border-l border-r border-b py-2 px-4 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-700">{{ count($bulkSelected) }} users selected</p>

            {{-- Delete Button --}}
            <button
                type="button"
                class="p-0 border-0 ml-2 text-red-600 text-sm font-medium hover:underline cursor-pointer"
                wire:click="deselectAll">
                Deselect all
            </button>
        </div>
    @endif

    {{-- Active Filters --}}
    @if ($createdFrom || $createdUntil)
        <div class="bg-transparent border-l border-r border-b py-2 px-4 flex items-center">
            <p class="text-sm font-semibold text-gray-700">Active filters</p>

            @if ($createdFrom)
                <div class="bg-blue-50 px-2 flex items-center ml-3 rounded-md">
                    <p class="text-xs font-medium text-blue-700 flex items-center gap-2">
                        Created from {{ \Carbon\Carbon::parse($createdFrom)->format('d F Y') }}
                        <button
                            type="button"
                            class="p-1 border-0 text-blue-700 cursor-pointer"
                            wire:click="resetFliters('createdFrom')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="" viewBox="0 2 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </button>
                    </p>
                </div>
            @endif

            @if ($createdUntil)
                <div class="bg-blue-50 px-2 flex items-center ml-3 rounded-md">
                    <p class="text-xs font-medium text-blue-700 flex items-center gap-2">
                        Created until {{ \Carbon\Carbon::parse($createdUntil)->format('d F Y') }}
                        <button
                            type="button"
                            class="p-1 border-0 text-blue-700 cursor-pointer"
                            wire:click="resetFliters('createdUntil')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="" viewBox="0 2 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </button>
                    </p>
                </div>
            @endif
        </div>
    @endif

    {{-- Video Table --}}
    <div class="border-l border-r border-b overflow-x-auto w-full">
        <table class="w-full table-auto whitespace-nowrap divide-y divide-gray-200 mb-0">
            <thead class="text-left font-semibold text-sm">
                <tr class="[&>th]:px-4 [&>th]:py-3 divide-x divide-none">
                    <th class="bg-transparent pl-4 py-3"></th>
                    <th class="bg-transparent py-3 whitespace-nowrap">Nama</th>

                    @if ($colPref && $colPref->value['colEmail'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Email</th>
                    @endif

                    @if ($colPref && $colPref->value['colCreatedAt'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Tanggal Dibuat</th>
                    @endif

                    <th class="bg-transparent py-3 whitespace-nowrap"></th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr 
                        wire:key="{{ $user->id }}" 
                        @class([
                            "[&>td]:px-4 [&>td]:py-3 divide-x divide-none",
                            "bg-blue-50" => in_array($user->id, $bulkSelected),
                        ])>
                        {{-- Select --}}
                        <td class="pl-4 py-3">
                            <input
                                type="checkbox"
                                name="selectUser"
                                class="border border-gray-500 border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                value="{{ $user->id }}"
                                wire:model.live="bulkSelected">
                        </td>

                        {{-- Name --}}
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-sm font-normal text-gray-800">{{ $user->name }}</p>
                        </td>

                        {{-- External Link --}}
                        @if ($colPref && $colPref->value['colEmail'])
                            <td class="py-3 whitespace-nowrap">
                                <p class="text-sm font-normal text-gray-800">{{ $user->email }}</p>
                            </td>
                        @endif

                        {{-- External Link --}}
                        @if ($colPref && $colPref->value['colCreatedAt'])
                            <td class="py-3 whitespace-nowrap">
                                <p class="text-sm font-normal text-gray-800">{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y') }}</p>
                            </td>
                        @endif

                        {{-- View, Edit & Delete Button --}}
                        <td class="pr-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                {{-- View Button --}}
                                {{-- <a href="{{ route('video.show', $video->id) }}" class="text-secondary p-0 border-0">
                                    <div class="flex items-center gap-1 text-gray-500 hover:text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" class="bi bi-eye-fill" viewBox="0 -1 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                                        </svg>
                                        <p class="text-sm font-medium hover:underline">View</p>
                                    </div>
                                </a> --}}

                                {{-- Edit Button --}}
                                {{-- <a href="{{ route('all-video.edit', $video->id) }}" class="ml-2">
                                    <div class="flex items-center gap-1 text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                                        </svg>
                                        <p class="text-sm font-medium hover:underline">Edit</p>
                                    </div>
                                </a> --}}

                                {{-- Delete Button --}}
                                <button class="ml-2 cursor-pointer" form="all-user.destroy-{{ $user->id }}">
                                    <div class="flex items-center gap-1 text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                        </svg>
                                        <p class="text-sm font-medium hover:underline">Delete</p>
                                    </div>
                                </button>

                                <form id="all-user.destroy-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border-b">
                            <div class="flex flex-col items-center py-4 ">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                    fill="#d1d7de" class="bi bi-x-circle-fill mb-1"
                                    viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0
                                            M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8
                                            l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707
                                            l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8
                                            l2.647-2.646a.5.5 0 0 0-.708-.708L8
                                            7.293z"/>
                                </svg>
                                <p class="mt-2 mb-0 text-black">User tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    {{ $users->links(data: ['scrollTo' => false]) }}
</div>
