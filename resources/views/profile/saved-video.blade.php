<x-layouts.app>
    <section class="bg-gray-50 min-h-[calc(100dvh-130px)] py-6">
        {{-- Container --}}
        <div class="container mx-auto flex flex-col lg:flex-row gap-x-6">
            {{-- Sidebar --}}
            <div class="min-w-[22%]">
                <x-profile-sidebar />
            </div>

            {{-- Content --}}
            <div class="flex-1">
                {{-- Title --}}
                <h1 class="text-xl mb-6 inline-flex items-center gap-2">
                    History
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
            </div>
        </div>
    </section>
</x-layouts.app>
