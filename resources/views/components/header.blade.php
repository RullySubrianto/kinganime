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

<header class="dark:bg-[#04000D] dark:text-white border-b shadow-xs">
    <div class="container mx-auto py-4.5 flex lg:flex-row justify-between items-center">
        {{-- Menu - Mobile --}}
        <div class="lg:hidden relative">
            <x-ui.button
                type="button"
                class="peer bg-transparent!"
                variant="link"
                size="icon">
                <x-icon name="three-lines-horizontal" class="w-6! h-6! text-black dark:text-white"/>
            </x-ui.button>

            <div
                class="absolute left-1 mt-2 w-auto rounded-md shadow-lg bg-white dark:bg-neutral-900
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
                                        class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded dark:text-white {{ $isActive ? 'text-primary bg-[#276add10]' : 'text-gray-500 hover:text-primary hover:bg-[#276add10]' }}">
                                        <x-icon :name="$sidebarItem['icon']" class="w-3 h-3" />
                                        <p class="text-sm">{{ $sidebarItem['label'] }}</p>
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <a 
                                    onclick="document.getElementById('logout-form').submit(); return false;"
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 dark:text-white hover:text-primary hover:bg-[#276add10]">
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
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 dark:text-white hover:text-primary hover:bg-[#276add10]">
                                    <x-icon name="history" class="w-3 h-3" />
                                    <p class="text-sm">History</p>
                                </a>
                            </li>

                            {{-- Login --}}
                            <li>
                                <a 
                                    href={{ route('login') }} 
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 dark:text-white hover:text-primary hover:bg-[#276add10]">
                                    <x-icon name="login" class="w-3 h-3" />
                                    <p class="text-sm">Masuk</p>
                                </a>
                            </li>

                            {{-- Sign Up --}}
                            <li>
                                <a 
                                    href={{ route('register') }} 
                                    class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 dark:text-white hover:text-primary hover:bg-[#276add10]">
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
            <x-icon name="logo" class="w-35 text-black dark:text-white" />
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
                class="py-1.5 ps-4 text-sm rounded-full! shadow-none! border dark:border-primary/90"
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
        <a 
            href="{{ route('search-video') }}"
            class="flex lg:hidden">
            <x-icon name="search" class="w-5 h-5" />
        </a>

        {{-- Menu, Login & Register, Profile --}}
        <ul class="hidden lg:flex items-center space-x-5 text-sm font-medium">

            {{-- Dark / Light Mode Toggle --}}
            <div 
                x-data 
                @click="
                    dark = !dark;
                    localStorage.setItem('dark', dark);
                "
                class="w-12 h-6 flex items-center bg-gray-300 dark:bg-gray-700 
                    rounded-full p-1 cursor-pointer transition-all duration-300"
            >
                {{-- Switch --}}
                <div 
                    class="w-5 h-5 bg-white dark:bg-black rounded-full shadow-md 
                        transform transition-transform duration-300 flex items-center justify-center"
                    :class="dark ? 'translate-x-6' : 'translate-x-0'"
                >
                    {{-- Icons --}}
                    <svg x-show="!dark" viewBox="0 0 24 24" class="w-3 h-3 text-yellow-500" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12Z" fill="currentcolor"></path> 
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M12 2C12.5523 2 13 2.44772 13 3V5C13 5.55228 12.5523 6 12 6C11.4477 6 11 5.55228 11 5V3C11 2.44772 11.4477 2 12 2Z" fill="currentcolor"></path> 
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M19.7071 4.29289C20.0976 4.68342 20.0976 5.31658 19.7071 5.70711L17.7071 7.70711C17.3166 8.09763 16.6834 8.09763 16.2929 7.70711C15.9024 7.31658 15.9024 6.68342 16.2929 6.29289L18.2929 4.29289C18.6834 3.90237 19.3166 3.90237 19.7071 4.29289Z" fill="currentcolor"></path> 
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M18 12C18 11.4477 18.4477 11 19 11H21C21.5523 11 22 11.4477 22 12C22 12.5523 21.5523 13 21 13H19C18.4477 13 18 12.5523 18 12Z" fill="currentcolor"></path> 
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M16.2929 16.2929C16.6834 15.9024 17.3166 15.9024 17.7071 16.2929L19.7071 18.2929C20.0976 18.6834 20.0976 19.3166 19.7071 19.7071C19.3166 20.0976 18.6834 20.0976 18.2929 19.7071L16.2929 17.7071C15.9024 17.3166 15.9024 16.6834 16.2929 16.2929Z" fill="currentcolor"></path> 
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M12 18C12.5523 18 13 18.4477 13 19V21C13 21.5523 12.5523 22 12 22C11.4477 22 11 21.5523 11 21V19C11 18.4477 11.4477 18 12 18Z" fill="currentcolor"></path> 
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M7.70711 16.2929C8.09763 16.6834 8.09763 17.3166 7.70711 17.7071L5.70711 19.7071C5.31658 20.0976 4.68342 20.0976 4.29289 19.7071C3.90237 19.3166 3.90237 18.6834 4.29289 18.2929L6.29289 16.2929C6.68342 15.9024 7.31658 15.9024 7.70711 16.2929Z" fill="currentcolor"></path> 
                        <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M2 12C2 11.4477 2.44772 11 3 11H5C5.55228 11 6 11.4477 6 12C6 12.5523 5.55228 13 5 13H3C2.44772 13 2 12.5523 2 12Z" fill="currentcolor"></path> <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L7.70711 6.29289C8.09763 6.68342 8.09763 7.31658 7.70711 7.70711C7.31658 8.09763 6.68342 8.09763 6.29289 7.70711L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z" fill="currentcolor"></path> 
                    </svg>

                    <svg x-show="dark" class="w-3 h-3 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </div>
            </div>

            {{-- History, Login & Register --}}
            @guest
                {{-- History --}}
                <a 
                    href="{{ route('history.index') }}"
                    class="text-primary dark:text-white hover:underline pe-4 border-e">
                    History
                </a>
                {{-- Login --}}
                <li>
                    <a 
                        href={{ route('login') }}
                        class="py-1 px-3 rounded-md border border-primary dark:border-white text-base hover:bg-primary/5 dark:hover:bg-white/5 text-primary dark:text-white">Masuk</a>
                </li>

                {{-- Sign Up --}}
                <li>
                    <a 
                        href={{ route('register') }}
                        class="py-1 px-3 rounded-md border border-primary dark:border-white text-base bg-primary dark:bg-white hover:bg-primary/90 dark:hover:bg-white/90 hover:border-primary/90 dark:hover:border-white/90 text-white dark:text-[#04000D]">Daftar</a>
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
                        class="absolute right-1 mt-2 w-auto rounded-md shadow-lg bg-white dark:bg-neutral-900
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
                                            class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded dark:text-white cursor-pointer {{ $isActive ? 'text-primary bg-[#276add10]' : 'text-gray-500 hover:text-primary hover:bg-[#276add10]' }}">
                                            <x-icon :name="$sidebarItem['icon']" class="w-3 h-3" />
                                            <p class="text-sm">{{ $sidebarItem['label'] }}</p>
                                        </a>
                                    </li>
                                @endforeach

                                <li>
                                    <a 
                                        onclick="document.getElementById('logout-form').submit(); return false;"
                                        class="flex flex-row items-center gap-2 py-1 ps-3 pe-8 rounded text-gray-500 hover:text-primary hover:bg-[#276add10] dark:text-white cursor-pointer">
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