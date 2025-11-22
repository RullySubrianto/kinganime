
@php
    $sidebarItems = [
        [
            'route' => 'profile.index',
            'label' => 'Informasi Pribadi',
            'icon' => 'user',
        ],
        [
            'route' => 'users.index',
            'label' => 'Kontrol Pengguna',
            'icon' => 'user-defense',
            'can'   => 'isAdmin',
        ],
        [
            'route' => 'all-video.index',
            'label' => 'Semua Video',
            'icon' => 'list',
            'can'   => 'isAdmin',
        ],
        [
            'route' => 'post.create',
            'label' => 'Post Video',
            'icon' => 'plus-circle',
            'can'   => 'isAdmin',
        ],
    ];
@endphp

<nav class="bg-white py-3 px-2 rounded-xl">
    <ul class="space-y-2">
        @foreach ($sidebarItems as $sidebarItem)
            {{-- Check permission --}}
            @if(isset($sidebarItem['can']) && !auth()->user()->can($sidebarItem['can']))
                @continue
            @endif

            {{-- Route --}}
            @php
                $isActive = request()->routeIs($sidebarItem['route']);
            @endphp

            <li>
                <a 
                    href={{ route($sidebarItem['route']) }} 
                    class="flex flex-row items-center gap-2 py-2 px-3 rounded {{ $isActive ? 'text-primary bg-[#276add10]' : 'text-gray-500 hover:text-primary hover:bg-[#276add10]' }}">
                    <x-icon :name="$sidebarItem['icon']" class="w-3.5 h-3.5" />
                    <p class="font-medium">{{ $sidebarItem['label'] }}</p>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
