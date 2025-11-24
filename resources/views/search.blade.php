<x-layouts.app>
    <section class="bg-gray-50 flex flex-col items-center min-h-[calc(100dvh-130px)]">
        {{-- Container --}}
        <div class="container space-y-6 py-6">
            {{-- Title --}}
            <h1 class="text-xl mb-6">Pencarian Video</h1>

            {{-- Search Bar --}}
            <form action="{{ route('search-video') }}" method="GET" class="relative">
                <x-ui.input 
                    type="text"
                    class="h-11 ps-4 text-sm rounded-md! shadow-none! border"
                    id="search"
                    name="search"
                    autocomplete="search"
                    placeholder="Cari Video..."
                    value="{{ $search ?? '' }}"/>
                <button
                    type="submit"
                    class="absolute right-1.5 top-1.5 text-white text-sm py-1.5 px-4 rounded-md bg-primary cursor-pointer">
                        Cari
                </button>

                <div class="flex flex-row justify-between items-center mt-4 flex-wrap gap-y-4">
                    @if ($search)
                        <p class="text-sm">Ditemukan <strong>{{ $videos->count() }}</strong> hasil untuk <strong>"{{ $search }}"</strong></p>
                    @else
                        <div></div>
                    @endif

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-muted-foreground">Urutkan:</span>
                        <div class="relative">
                            <select 
                                id="orderBy"
                                name="orderBy"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-primary disabled:cursor-not-allowed disabled:opacity-50 min-w-20 text-base appearance-none pr-8 text-muted-foreground">
                                <option value="desc" @selected($orderBy === 'desc')>Terbaru</option>
                                <option value="asc" @selected($orderBy === 'asc')>Terlama</option>
                            </select>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="absolute right-2 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground pointer-events-none" aria-hidden="true">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Videos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($videos as $video)
                    {{-- Video --}}
                    <x-video-card :video="$video"/>
                @empty
                @endforelse
            </div>

            {{-- Pagination --}}
            {{ $videos->links() }}
        </div>
    </section>
</x-layouts.app>
