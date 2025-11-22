<header class="bg-white border-b shadow-xs">
    <div class="container mx-auto py-4.5 flex flex-row-reverse lg:flex-row justify-between items-center">
        {{-- Logo --}}
        <a href={{ route('index') }}>
            <img src="/images/logo.svg" alt="Logo">
        </a>

        {{-- Search Bar --}}
        <form action="" class="relative">
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

        {{-- Menu, Login & Register, Profile --}}
        <ul class="hidden lg:flex items-center space-x-5 text-sm font-medium">

            {{-- Menu --}}
            <div class="relative group">
                {{-- Trigger --}}
                <button
                    type="button"
                    class="inline-flex items-center justify-center px-6 border-e text-primary">
                    Menu
                </button>

                {{-- Panel --}}
                <div
                    class="absolute right-6 mt-2 w-auto rounded-md shadow-lg bg-white
                        ring-1 ring-black/5 z-50
                        opacity-0 invisible
                        group-hover:opacity-100 group-hover:visible
                        transition-all duration-300 
                        delay-200 group-hover:delay-75">
                    <div class="p-2 text-sm flex flex-col w-max min-w-fit">
                        Menu
                    </div>
                </div>
            </div>


            {{-- Login & Register --}}
            @guest
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
                <x-ui.button
                    type="button"
                    class="bg-primary-foreground p-5 rounded-full!"
                    variant="link"
                    size="icon">
                    <x-icon name="user-fill" />
                </x-ui.button>
            @endauth
        </ul>
    </div>
</header>