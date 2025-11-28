
@php
    $sidebarItems = [
        [
            'route' => 'profile.index',
            'label' => 'Informasi Pribadi',
            'icon' => 'user',
            'access'   => 'auth',
        ],
        [
            'route' => 'users.index',
            'label' => 'Kontrol Pengguna',
            'icon' => 'user-defense',
            'access'   => 'can:isAdmin',
        ],
        [
            'route' => 'all-video.index',
            'label' => 'Semua Video',
            'icon' => 'list',
            'access'   => 'can:isAdmin',
        ],
        [
            'route' => 'post.create',
            'label' => 'Post Video',
            'icon' => 'plus-circle',
            'access'   => 'can:isAdmin',
        ],
        [
            'route' => 'category.index',
            'label' => 'Kategori',
            'icon' => 'category',
            'access'   => 'can:isAdmin',
        ],
        [
            'route' => 'category.create',
            'label' => 'Tambah Kategori',
            'icon' => 'plus-circle',
            'access'   => 'can:isAdmin',
        ],
        [
            'route' => 'saved-video.index',
            'label' => 'Favorit',
            'icon' => 'heart',
            'access'   => 'auth',
        ],
        [
            'route' => 'history.index',
            'url' => '/history',
            'label' => 'History',
            'icon' => 'history',
            'access'   => 'public',
        ],
    ];
@endphp

<nav class="bg-white dark:bg-background dark:border py-3 px-2 rounded-xl">
    <ul class="space-y-2">
        @foreach ($sidebarItems as $sidebarItem)
            {{-- Check permission --}}
            @php $access = $sidebarItem['access'] ?? 'public'; @endphp

            @if($access === 'auth' && !auth()->check())
                @continue
            @endif

            @if(Str::startsWith($access, 'can:') && !auth()->user()?->can(Str::after($access, 'can:')))
                @continue
            @endif

            {{-- Route --}}
            @php
                $isActive = false;

                // 1. Check route name first (only valid if route exists and matches)
                if (isset($sidebarItem['route']) && request()->routeIs($sidebarItem['route'])) {
                    $isActive = true;

                // 2. Otherwise check URL if defined
                } elseif (isset($sidebarItem['url']) && str(request()->path())->startsWith(ltrim($sidebarItem['url'], '/'))) {
                    $isActive = true;
                }
            @endphp

            <li>
                <a 
                    href={{ route($sidebarItem['route']) }} 
                    class="flex flex-row items-center gap-2 py-2 px-3 dark:text-white rounded {{ $isActive ? 'text-primary bg-[#276add10]' : 'text-gray-500 hover:text-primary hover:bg-[#276add10]' }}">
                    <x-icon :name="$sidebarItem['icon']" class="w-3.5 h-3.5" />
                    <p class="font-medium">{{ $sidebarItem['label'] }}</p>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
