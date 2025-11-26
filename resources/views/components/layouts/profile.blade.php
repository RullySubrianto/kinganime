@props(['contentClass' => '', 'title' => null])

<x-layouts.app :title="$title">
    <section class="bg-gray-50 min-h-[calc(100dvh-130px)] py-6">
        {{-- Container --}}
        <div class="container mx-auto flex flex-col lg:flex-row gap-x-6">
            {{-- Sidebar --}}
            <div class="min-w-[22%] hidden lg:block">
                <x-profile-sidebar />
            </div>

            {{-- Content --}}
            <div class="flex-1 {{ $contentClass }}">
                {{ $slot }}
            </div>
        </div>
    </section>
</x-layouts.app>
