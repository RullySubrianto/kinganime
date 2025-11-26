<x-layouts.profile title="Video Favorit">
    {{-- Title --}}
    <h1 class="text-2xl font-medium mb-4 inline-flex items-center gap-2">
        Favorit
        <x-icon name="heart" class="w-5 h-5 fill-primary text-primary"/>
    </h1>

    {{-- Videos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($videos as $video)
            {{-- Video --}}
            <x-video-card :video="$video"/>
        @empty
            <span>Belum ada video favorit, 
                <a 
                    href="{{ route('search-video') }}"
                    class="text-blue-700 hover:underline">
                    Cari Video
                </a>
            </span>
        @endforelse
    </div>

    {{-- Pagination --}}
    {{ $videos->links() }}
</x-layouts.profile>
