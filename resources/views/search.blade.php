<x-layouts.app title="Cari Video">
    <section class="flex flex-col items-center min-h-[calc(100dvh-130px)]">
        {{-- Container --}}
        <div class="container space-y-6 py-6">
            {{-- Title --}}
            <h1 class="text-xl mb-6">Pencarian Video</h1>

            {{-- Search Bar --}}
            <form action="{{ route('search-video') }}" method="GET" class="relative search-form">
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

                {{-- Tag Piils --}}
                <div id="recent-tags" class="flex flex-row flex-wrap mt-4 gap-2 {{ count(json_decode($_COOKIE['recentSearches'] ?? '[]')) ? 'mb-6' : '' }}"></div>

                {{-- Found, Sort By --}}
                <div class="flex flex-row justify-between items-center flex-wrap gap-y-4">
                    {{-- Found --}}
                    @if ($search)
                        <p class="text-sm">Ditemukan <strong>{{ $videos->total() }}</strong> hasil untuk <strong>"{{ $search }}"</strong></p>
                    @else
                        <div></div>
                    @endif

                    {{-- Sort By --}}
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
    
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            renderRecentTags();
        });

        // Render Tag Pills
        function renderRecentTags() {
            const container = document.getElementById("recent-tags");
            if (!container) return;

            const tags = JSON.parse(localStorage.getItem("recentSearches")) || [];
            container.innerHTML = "";

            // --- Add/remove margin depending on if tags exist ---
            if (tags.length > 0) {
                container.classList.add("mb-4");
            } else {
                container.classList.remove("mb-4");
            }

            tags.forEach(tag => {

                // Pill wrapper
                const pill = document.createElement("div");
                pill.className =
                    "flex items-center gap-2 py-1.5 px-4 bg-gray-100 text-black rounded-full text-sm " +
                    "dark:bg-white/10 dark:text-white";

                // Pill text (click → search)
                const link = document.createElement("a");
                link.href = `/cari-video?search=${encodeURIComponent(tag)}&orderBy=desc`;
                link.textContent = tag;
                link.className = "hover:text-primary dark:hover:text-gray-200";

                // Close icon (X)
                const closeBtn = document.createElement("button");
                closeBtn.innerHTML = "&times;";
                closeBtn.className =
                    "text-gray-500 hover:text-red-500 dark:text-gray-300 dark:hover:text-red-400 ml-1 cursor-pointer";
                closeBtn.style.fontSize = "16px";
                closeBtn.style.lineHeight = "12px";

                // Remove tag after clicking close
                closeBtn.addEventListener("click", (e) => {
                    e.preventDefault(); // Prevent navigation
                    e.stopPropagation(); // Prevent triggering the pill link

                    removeTag(tag);
                });

                pill.appendChild(link);
                pill.appendChild(closeBtn);
                container.appendChild(pill);
            });
        }

        // Remove a tag & re-render
        function removeTag(tag) {
            let tags = JSON.parse(localStorage.getItem("recentSearches")) || [];

            tags = tags.filter(t => t.toLowerCase() !== tag.toLowerCase());

            localStorage.setItem("recentSearches", JSON.stringify(tags));

            renderRecentTags();
        }

        // Intercept Submit & Save Search
        document.addEventListener("submit", (e) => {
            const form = e.target;

            if (!form.matches('.search-form')) return;

            e.preventDefault();

            const searchInput = form.querySelector("#search");
            const value = searchInput.value.trim();
            if (!value) {
                form.submit();
                return;
            }

            let tags = JSON.parse(localStorage.getItem("recentSearches")) || [];

            tags = tags.filter(t => t.toLowerCase() !== value.toLowerCase());
            tags.unshift(value);
            tags = tags.slice(0, 8);

            localStorage.setItem("recentSearches", JSON.stringify(tags));

            const order = document.querySelector("#orderBy")?.value ?? "desc";
            window.location.href = `/cari-video?search=${encodeURIComponent(value)}&orderBy=${order}`;
        });
    </script>

    </section>
</x-layouts.app>
