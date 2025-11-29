@props(['contentClass' => '', 'title' => null])

<!DOCTYPE html>
<html lang="en" x-data="{ dark: localStorage.getItem('dark') === 'true' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . " - " . config('app.name') : config('app.name') }}</title>

    <script>
        // Prevent white flash by setting dark class before CSS loads
        const isDark = localStorage.getItem('dark');
        if (isDark === null || isDark === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="flex flex-col min-h-screen">
        {{-- Header --}}
        <x-header />

        {{-- Content --}}
        <div class="flex flex-1 flex-col font-rubik bg-gray-50 dark:bg-background {{ $contentClass }}">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <x-footer />
    </main>

    {{-- Required for Alpine to work anywhere --}}
    @livewireScripts

    {{-- Flash Message --}}
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
                px-4 py-3 rounded-xl shadow-lg text-sm flex items-center gap-3
                bg-white text-neutral-800 border border-neutral-200 
                dark:bg-neutral-900 dark:text-neutral-100 dark:border-neutral-700
            ">
                {{-- Icon --}}
                <x-icon 
                    :name="session('success') ? 'check-circle' : 'x-circle'" 
                    class="w-4 h-4 text-neutral-600 dark:text-neutral-300"
                />

                {{-- Message --}}
                <p class="text-sm font-medium">
                    {{ session('success') ?? session('error') }}
                </p>
            </div>
        </div>
    @endif
</body>
</html>