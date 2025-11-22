@props(['contentClass' => ''])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>

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
</body>
</html>