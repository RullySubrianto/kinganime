<div class="col p-0 overflow-x-auto">
    {{-- Success & Error Flash Message --}}
    {{-- <x-profile.success-and-error-flash-message /> --}}

    {{-- Search Bar, Bulk Actions --}}
    <div class="bg-white dark:bg-neutral-900 dark:border-neutral-700 border rounded-t-xl py-3 pl-4 pr-3">
        {{-- Bulk Actions, Columns, Filters, Search Bar --}}
        <div class="flex justify-between items-center">
            {{-- Bulk Actions --}}
            @if (count($bulkSelected) > 0)
                <div x-data="{ open: false }" class="relative">
                    <button
                        type="button"
                        @click="open = !open"
                        @click.outside="open = false"
                        class="flex items-center border dark:border-neutral-700 rounded-lg py-2 px-3 bg-gray-100 dark:bg-neutral-800 hover:bg-gray-200 dark:hover:bg-neutral-700 text-sm gap-2"
                        aria-haspopup="true"
                        :aria-expanded="open ? 'true' : 'false'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="text-gray-600 dark:text-white" viewBox="0 0 16 16">
                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-700 dark:text-white">Bulk actions</p>
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        class="absolute left-0 mt-2 bg-white hover:bg-gray-50 dark:bg-neutral-900 dark:hover:bg-neutral-700 rounded-lg shadow-md border w-52 py-1 z-5"
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
                            class="text-gray-500 dark:text-gray-300 p-1 hover:text-gray-800 dark:hover:text-white relative"
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
                            class="absolute right-0 mt-2 bg-white dark:bg-neutral-900 shadow-lg border dark:border-neutral-700 rounded-lg p-4 w-72 z-50"
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
                                    class="w-full bg-primary text-white py-2 rounded-md text-sm font-semibold hover:bg-primary/90 cursor-pointer">
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
                            class="absolute right-0 mt-2 bg-white dark:bg-neutral-900 shadow-lg border dark:border-neutral-700 rounded-lg p-4 w-72 z-50"
                            style="display:none;">
                            
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-semibold">Columns</p>
                                <button type="button" wire:click="resetColPref" class="text-red-600 text-xs font-semibold hover:underline cursor-pointer">Reset</button>
                            </div>

                            <form wire:submit.prevent="saveColPref" class="mt-3 flex flex-col gap-3">
                                <label class="flex items-center gap-2 text-sm dark:text-gray-300">
                                    <input disabled checked type="checkbox" />
                                    Judul
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer dark:text-gray-300">
                                    <input wire:model="colThumbnail" type="checkbox" class="cursor-pointer"/>
                                    Thumbnail
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer dark:text-gray-300">
                                    <input wire:model="colExternalLink" type="checkbox" class="cursor-pointer"/>
                                    Link Eksternal
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer dark:text-gray-300">
                                    <input wire:model="colStatus" type="checkbox" class="cursor-pointer"/>
                                    Status
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer dark:text-gray-300">
                                    <input wire:model="colViewsCount" type="checkbox" class="cursor-pointer"/>
                                    Viewers
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer dark:text-gray-300">
                                    <input wire:model="colViewsCountReal" type="checkbox" class="cursor-pointer"/>
                                    Viewers Asli
                                </label>

                                <label class="flex items-center gap-2 text-sm cursor-pointer dark:text-gray-300">
                                    <input wire:model="colCategory" type="checkbox" class="cursor-pointer"/>
                                    Kategori
                                </label>

                                <label class="flex items-center gap-2 text-sm dark:text-gray-300">
                                    <input disabled checked type="checkbox" />
                                    Tanggal Dibuat
                                </label>

                                {{-- Apply --}}
                                <button
                                    type="submit"
                                    @click="open = null"
                                    class="mt-2 w-full bg-primary text-white py-2 rounded-md text-sm font-semibold hover:bg-primary/90 cursor-pointer">
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
                        class="py-1.5 ps-4 text-sm rounded-lg shadow-none! border dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-800 dark:text-gray-200"
                        id="searchVideo"
                        name="searchVideo"
                        autocomplete="searchVideo"
                        placeholder="Cari Video..."
                        wire:model.live.debounce.500ms="searchVideo"/>
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
        <div class="bg-transparent dark:bg-neutral-900 border-l border-r border-b dark:border-neutral-700 py-2 px-4 flex items-center justify-between">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ count($bulkSelected) }} videos selected</p>

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
        <div class="bg-transparent border-l border-r border-b dark:border-neutral-700 py-2 px-4 flex items-center">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Active filters</p>

            @if ($createdFrom)
                <div class="bg-purple-50 dark:bg-neutral-900 px-2 flex items-center ml-3 rounded-md">
                    <p class="text-xs font-medium text-primary dark:text-white flex items-center gap-2">
                        Created from {{ \Carbon\Carbon::parse($createdFrom)->format('d F Y') }}
                        <button
                            type="button"
                            class="p-1 border-0 text-primary dark:text-white cursor-pointer"
                            wire:click="resetFliters('createdFrom')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="" viewBox="0 2 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                            </svg>
                        </button>
                    </p>
                </div>
            @endif

            @if ($createdUntil)
                <div class="bg-purple-50 dark:bg-neutral-900 px-2 flex items-center ml-3 rounded-md">
                    <p class="text-xs font-medium text-primary dark:text-white flex items-center gap-2">
                        Created until {{ \Carbon\Carbon::parse($createdUntil)->format('d F Y') }}
                        <button
                            type="button"
                            class="p-1 border-0 text-primary dark:text-white cursor-pointer"
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
    <div 
        x-data="{ showDelete: false, deleteId: null }"
        class="border-l border-r border-b dark:border-neutral-700 overflow-x-auto w-full">
        <table class="w-full table-auto whitespace-nowrap divide-y divide-gray-200 mb-0">
            <thead class="text-left font-semibold text-sm dark:bg-neutral-900 dark:text-gray-200">
                <tr class="[&>th]:px-4 [&>th]:py-3 divide-x divide-none">
                    <th class="bg-transparent pl-4 py-3"></th>
                    <th class="bg-transparent py-3 whitespace-nowrap">Judul</th>

                    @if ($colPref && $colPref->value['colThumbnail'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Thumbnail</th>
                    @endif

                    @if ($colPref && $colPref->value['colExternalLink'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Link Eksternal</th>
                    @endif

                    @if ($colPref && $colPref->value['colStatus'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Status</th>
                    @endif

                    @if ($colPref && $colPref->value['colViewsCount'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Viewers</th>
                    @endif

                    @if ($colPref && $colPref->value['colViewsCountReal'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Viewers Asli</th>
                    @endif

                    @if ($colPref && $colPref->value['colCategory'])
                        <th class="bg-transparent py-3 whitespace-nowrap">Kategori</th>
                    @endif

                    <th class="bg-transparent py-3 whitespace-nowrap">Tanggal Dibuat</th>
                    <th class="bg-transparent py-3 whitespace-nowrap"></th>
                </tr>
            </thead>

            <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-100 dark:divide-neutral-700">
                @forelse($videos as $video)
                    <tr 
                        wire:key="{{ $video->id }}" 
                        @class([
                            "[&>td]:px-4 [&>td]:py-3 divide-x divide-none",
                            "bg-blue-50 dark:bg-neutral-800" => in_array($video->id, $bulkSelected),
                        ])>
                        {{-- Select --}}
                        <td class="pl-4 py-3">
                            <input
                                type="checkbox"
                                name="selectVideo"
                                class="border border-gray-500 border-default-medium rounded-xs bg-neutral-secondary-medium focus:ring-2 focus:ring-brand-soft"
                                value="{{ $video->id }}"
                                wire:model.live="bulkSelected">
                        </td>

                        {{-- Title --}}
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-sm font-normal text-gray-800 dark:text-gray-200">{{ $video->title }}</p>
                        </td>

                        {{-- Thumbnail --}}
                        @if ($colPref && $colPref->value['colThumbnail'])
                            <td class="py-3 whitespace-nowrap">
                                <img 
                                    src="{{ $video->thumbnail }}"
                                    alt="Thumbnail"
                                    class="w-20 aspect-video rounded-md object-cover bg-gray-100">
                            </td>
                        @endif

                        {{-- External Link --}}
                        @if ($colPref && $colPref->value['colExternalLink'])
                            <td class="py-3 whitespace-nowrap">
                                <a href="{{ $video->external_link }}" class="text-sm font-normal text-blue-500 hover:underline">{{ $video->external_link }}</a>
                            </td>
                        @endif

                        {{-- Status --}}
                        @if ($colPref && $colPref->value['colStatus'])
                            <td class="py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($video->status === 'published')
                                        <div class="bg-green-50 dark:bg-green-900/40 py-1 px-2 rounded-md">
                                            <p class="text-xs font-medium dark:text-green-300!" style="color: #16a34a;">Jadi Duit</p>
                                        </div>
                                    @elseif ($video->status === 'draft')
                                        <div class="bg-blue-50 dark:bg-blue-900/40 py-1 px-2 rounded-md">
                                            <p class="text-xs font-medium dark:text-blue-300!" style="color: #0669d9;">Draf</p>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        @endif

                        {{-- Views Count --}}
                        @if ($colPref && $colPref->value['colViewsCount'])
                            <td class="py-3 whitespace-nowrap">
                                <p class="text-sm font-normal text-gray-800 dark:text-gray-200">{{ \Illuminate\Support\Number::abbreviate($video->views_count) }} Views</p>
                            </td>
                        @endif

                        {{-- Real Views Count --}}
                        @if ($colPref && $colPref->value['colViewsCountReal'])
                            <td class="py-3 whitespace-nowrap">
                                <p class="text-sm font-normal text-gray-800 dark:text-gray-200">{{ \Illuminate\Support\Number::abbreviate($video->views_count_real) }} Views</p>
                            </td>
                        @endif

                        {{-- Category --}}
                        @if ($colPref && $colPref->value['colCategory'])
                            <td class="py-3 whitespace-nowrap">
                                @foreach ($video->categories as $videoCategory)
                                    <a 
                                        href="{{ route('category.show', $videoCategory->id) }}" 
                                        class="text-sm font-normal text-blue-500 hover:underline">
                                        {{ $videoCategory->name }}
                                    </p>
                                @endforeach
                            </td>
                        @endif

                        {{-- Created at --}}
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-sm font-normal text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($video->created_at)->format('d F Y') }}</p>
                        </td>

                        {{-- View, Edit & Delete Button --}}
                        <td class="pr-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                {{-- View Button --}}
                                <a href="{{ route('video.show', $video->slug) }}" class="text-secondary p-0 border-0">
                                    <div class="flex items-center gap-1 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                                        <x-icon name="eye" class="w-3.5 h-3.5"/>
                                        <p class="text-sm font-medium hover:underline">View</p>
                                    </div>
                                </a>

                                {{-- Edit Button --}}
                                <a href="{{ route('all-video.edit', $video->slug) }}" class="ml-2">
                                    <div class="flex items-center gap-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        <x-icon name="pen" class="w-3 h-3"/>
                                        <p class="text-sm font-medium hover:underline">Edit</p>
                                    </div>
                                </a>

                                {{-- Delete Button --}}
                                <button 
                                    type="button" 
                                    @click="deleteId = {{ $video->id }}; showDelete = true"
                                    class="ml-2 cursor-pointer"
                                >
                                    <div class="flex items-center gap-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                        <x-icon name="trash" class="w-3.5 h-3.5"/>
                                        <p class="text-sm font-medium hover:underline">Delete</p>
                                    </div>
                                </button>

                                <form id="all-video.destroy-{{ $video->id }}" action="{{ route('all-video.destroy', $video->slug) }}" method="POST" class="hidden">
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
                                <p class="mt-2 mb-0 text-black dark:text-white">Video tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Delete Modal --}}
        <template x-if="showDelete">
            <div 
                class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-999"
                x-transition.opacity
                @click.self="showDelete = false"
            >
                <div 
                    class="bg-white dark:bg-neutral-900 rounded-lg shadow-xl w-full max-w-md p-6"
                    x-transition.scale
                >
                    <h2 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Hapus Video?</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                        Apakah anda yakin ingin menghapus video ini? Tindakan ini tidak dapat dibatalkan.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button 
                            @click="showDelete = false" 
                            class="px-4 py-2 rounded-md bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-neutral-600 cursor-pointer"
                        >
                            Batal
                        </button>

                        <button 
                            @click="document.getElementById(`all-video.destroy-${deleteId}`).submit()" 
                            class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 cursor-pointer"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Pagination --}}
    {{ $videos->links(data: ['scrollTo' => false]) }}
</div>
