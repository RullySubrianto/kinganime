<x-layouts.app title="Beranda">
    <section class="bg-gray-50 flex flex-col items-center min-h-[calc(100dvh-130px)]">
        {{-- Container --}}
        <div class="container space-y-6 py-6">
            {{-- Tabs --}}
            <div class="self-start flex flex-row overflow-x-auto whitespace-nowrap scrollbar-hide gap-4">
                {{-- Tab --}}
                @foreach ($categories as $category)
                    <a 
                        href="{{ route('category.show', $category) }}"
                        class="py-2.5 px-5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            {{-- Videos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($videos as $video)
                    {{-- Video --}}
                    <x-video-card :video="$video"/>
                @endforeach
            </div>

            {{-- Pagination --}}
            {{ $videos->links() }}
        </div>
    </section>
</x-layouts.app>
