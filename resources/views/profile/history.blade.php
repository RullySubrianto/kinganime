<x-layouts.app title="History">
    <section class="min-h-[calc(100dvh-130px)] py-6">
        {{-- Container --}}
        <div class="container mx-auto flex flex-col lg:flex-row gap-x-6">
            {{-- Sidebar --}}
            @auth
                <div class="min-w-[22%] hidden lg:block">
                    <x-profile-sidebar />
                </div>
            @endauth

            {{-- Content --}}
            <div class="flex-1">
                {{-- Title --}}
                <h1 class="text-2xl font-medium mb-4 inline-flex items-center gap-2">
                    History
                    <x-icon name="history" class="w-5 h-5"/>
                </h1>

                {{-- Videos --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    {{-- Auth --}}
                    @if ($videos)
                        @forelse ($videos as $video)
                            {{-- Video --}}
                            <x-video-card :video="$video"/>
                        @empty
                            <span>Belum ada history, 
                                <a 
                                    href="{{ route('search-video') }}"
                                    class="text-blue-700 hover:underline">
                                    Cari Video
                                </a>
                            </span>
                        @endforelse

                        {{-- Pagination --}}
                        {{ $videos->links() }}
                    {{-- Guest --}}
                    @else
                        @if (!auth()->check())
                            <script>
                                let ids = JSON.parse(localStorage.getItem('video_history') || "[]")

                                if (!window.location.pathname.includes('/history-guest')) {
                                        let ids = JSON.parse(localStorage.getItem('video_history') || "[]");

                                        if (ids.length) {
                                            let params = new URLSearchParams();
                                            ids.forEach(id => params.append('ids[]', id));

                                            window.location.href = `/history-guest?${params.toString()}`;
                                        }
                                    }
                            </script>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
