@props(['contentClass' => '', 'title' => null])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . " - " . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="flex flex-col min-h-screen">
        {{-- Header --}}
        <x-header />

        {{-- Content --}}
        <div class="flex flex-1 flex-col font-rubik {{ $contentClass }}">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <x-footer />
    </main>

    {{-- Required for Alpine to work anywhere --}}
    @livewireScripts

    @if (session('success') || session('error'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 6000)" 
            x-show="show"
            x-transition:enter="transition transform ease-out duration-300"
            x-transition:enter-start="translate-y-4 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition transform ease-in duration-200"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-4 opacity-0"
            class="fixed top-5 right-5 z-50"
        >
            <div class="
                px-4 py-3 
                rounded-lg shadow-lg text-black text-sm
                flex items-center gap-2
                bg-white
            ">
                {{-- Icon --}}
                @if(session('success'))
                    <x-icon name="check-circle" class="w-4 h-4" />
                @else
                    <x-icon name="x-circle" class="w-4 h-4" />
                @endif

                {{-- Message --}}
                <p class="text-sm font-medium">
                    {{ session('success') ?? session('error') }}
                </p>
            </div>
        </div>
    @endif

</body>
</html>