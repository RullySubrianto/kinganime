
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

<header class="bg-white border-b shadow-xs">
    <div class="container mx-auto py-4.5 flex lg:flex-row justify-between items-center">
        {{-- Menu - Mobile --}}
        <div class="lg:hidden relative">
            <x-ui.button
                type="button"
                class="peer bg-transparent! text-black!"
                variant="link"
                size="icon">
                <x-icon name="three-lines-horizontal" class="w-6! h-6!"/>
            </x-ui.button>

            <div
                class="absolute left-1 mt-2 w-auto rounded-md shadow-lg bg-white
                    ring-1 ring-black/5 z-50
                    opacity-0 scale-95 invisible
                    peer-focus:opacity-100 peer-focus:visible peer-focus:scale-100
                    transition-all duration-200">
                <nav class="p-2 text-sm flex flex-col w-max min-w-fit">
                    <ul class="space-y-2">
                        @auth
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
                                        class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded {{ $isActive ? 'text-primary bg-[#276add10]' : 'text-gray-500 hover:text-primary hover:bg-[#276add10]' }}">
                                        <x-icon :name="$sidebarItem['icon']" class="w-3 h-3" />
                                        <p class="text-sm">{{ $sidebarItem['label'] }}</p>
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <a 
                                    onclick="document.getElementById('logout-form').submit(); return false;"
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 hover:text-primary hover:bg-[#276add10]">
                                    <x-icon name="logout" class="w-3 h-3" />
                                    <p class="text-sm">Keluar</p>
                                </a>
                            </li>
                        @endauth

                        @guest
                            {{-- History --}}
                            <li>
                                <a 
                                    href={{ route('history.index') }} 
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 hover:text-primary hover:bg-[#276add10]">
                                    <x-icon name="history" class="w-3 h-3" />
                                    <p class="text-sm">History</p>
                                </a>
                            </li>

                            {{-- Login --}}
                            <li>
                                <a 
                                    href={{ route('login') }} 
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 hover:text-primary hover:bg-[#276add10]">
                                    <x-icon name="login" class="w-3 h-3" />
                                    <p class="text-sm">Masuk</p>
                                </a>
                            </li>

                            {{-- Sign Up --}}
                            <li>
                                <a 
                                    href={{ route('register') }} 
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 hover:text-primary hover:bg-[#276add10]">
                                    <x-icon name="register" class="w-3 h-3" />
                                    <p class="text-sm">Daftar</p>
                                </a>
                            </li>
                        @endguest
                    </ul>
                </nav>
            </div>
        </div>

        {{-- Logo --}}
        <a href={{ route('index') }}>
            <img src="/images/logo.svg" alt="Logo">
        </a>

        {{-- Search Bar --}}
        <form 
            action="{{ route('search-video') }}" 
            method="GET" 
            @class([
                'relative hidden lg:flex',
                'hidden' => request()->routeIs('search-video'),
            ])>
            <x-ui.input 
                type="text"
                class="py-1.5 ps-4 text-sm rounded-full! shadow-none! border"
                id="search"
                name="search"
                autocomplete="search"
                placeholder="Cari Video..."/>
            <button
                type="submit"
                class="absolute right-2 top-1 rounded-full p-1.5 bg-primary cursor-pointer">
                    <x-icon name="search" class="h-3.5 w-3.5 text-white"/>
            </button>
        </form>

        {{-- Search Bar - Mobile --}}
        <div class="flex lg:hidden">
            <x-icon name="search" class="w-5 h-5" />
        </div>

        {{-- Menu, Login & Register, Profile --}}
        <ul class="hidden lg:flex items-center space-x-5 text-sm font-medium">

            {{-- Login & Register --}}
            @guest
                <a 
                    href="{{ route('history.index') }}"
                    class="text-primary hover:underline pe-4 border-e">
                    History
                </a>
                {{-- Login --}}
                <li>
                    <a 
                        href={{ route('login') }}
                        class="py-1 px-3 rounded-md border border-primary text-base hover:bg-primary/5 text-primary">Masuk</a>
                </li>

                {{-- Sign Up --}}
                <li>
                    <a 
                        href={{ route('register') }}
                        class="py-1 px-3 rounded-md border border-primary text-base bg-primary hover:bg-primary/90 hover:border-primary/90 text-white">Daftar</a>
                </li>
            @endguest

            {{-- Profile --}}
            @auth
                <div class="relative group">
                    <x-ui.button
                        type="button"
                        class="bg-primary-foreground p-5 rounded-full!"
                        variant="link"
                        size="icon">
                        <x-icon name="user-fill" />
                    </x-ui.button>

                    <div
                        class="absolute right-1 mt-2 w-auto rounded-md shadow-lg bg-white
                            ring-1 ring-black/5 z-50
                            opacity-0 invisible
                            group-hover:opacity-100 group-hover:visible
                            transition-all duration-300 
                            delay-200 group-hover:delay-75">
                        <nav class="p-2 text-sm flex flex-col w-max min-w-fit">
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
                                            class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded cursor-pointer {{ $isActive ? 'text-primary bg-[#276add10]' : 'text-gray-500 hover:text-primary hover:bg-[#276add10]' }}">
                                            <x-icon :name="$sidebarItem['icon']" class="w-3 h-3" />
                                            <p class="text-sm">{{ $sidebarItem['label'] }}</p>
                                        </a>
                                    </li>
                                @endforeach

                                <li>
                                    <a 
                                        onclick="document.getElementById('logout-form').submit(); return false;"
                                        class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 hover:text-primary hover:bg-[#276add10] cursor-pointer">
                                        <x-icon name="logout" class="w-3 h-3" />
                                        <p class="text-sm">Keluar</p>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            @endauth
        </ul>
    </div>

    <form action="{{ route('logout') }}" method="POST" id="logout-form">
        @csrf
    </form>
</header>