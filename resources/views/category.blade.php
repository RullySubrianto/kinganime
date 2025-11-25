<x-layouts.app>
    <section class="bg-gray-50 flex flex-col items-center min-h-[calc(100dvh-130px)]">
        {{-- Container --}}
        <div class="container py-6">
            {{-- Title --}}
            <h1 class="text-2xl font-medium mb-4 inline-flex items-center gap-2">{{ $category->name }}</h1>

            {{-- Videos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
                {{-- Auth --}}
                @forelse ($videos as $video)
                    {{-- Video --}}
                    <x-video-card :video="$video"/>
                @empty
                    <span>Belum ada video, 
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
        </div>
    </section>
</x-layouts.app>
