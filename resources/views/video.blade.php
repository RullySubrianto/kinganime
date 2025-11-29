<x-layouts.app title="Video {{ $video->title }}">
    <section class="flex flex-col items-center min-h-[calc(100dvh-130px)]">
        {{-- Container --}}
        <div class="container py-6">
            {{-- Video --}}
            <article class="mb-6">
                <video 
                    autoplay="true" 
                    controls="" 
                    class="h-120 w-full aspect-video"
                    id="video" 
                    playsinline="" 
                    src="{{ $video->external_link }}">
                    <source type="video/mp4">
                    Your browser does not support the video tag.
                </video>

                {{-- Title & Buttons--}}
                <div class="mt-4 flex flex-row justify-between px-2">
                    {{-- Title --}}
                    <div class="space-y-2">
                        <h1 class="text-2xl font-medium">{{ $video->title }}</h1>
                        <p class="text-sm">{{ \Illuminate\Support\Number::abbreviate($video->views_count) }} Views</p>
                    </div>

                    {{--  Buttons --}}
                    <div class="flex flex-row gap-4">
                        {{-- Save To Watchlist Button --}}
                        <form 
                            action={{ route('video.save.watchlist', $video->slug) }}
                            method="POST">
                            @csrf

                            <x-ui.button 
                                type="submit"
                                variant="link"
                                size="icon"
                                class="w-full text-left flex flex-row items-center justify-between gap-3 rounded">
                                <x-icon name="watchlist" class="w-6! h-6! text-black dark:text-white"/>
                            </x-ui.button>
                        </form>

                        {{-- Save Button --}}
                        <form 
                            action={{ route('video.save', $video->slug) }}
                            method="POST">
                            @csrf

                            <x-ui.button 
                                type="submit"
                                variant="link"
                                size="icon"
                                class="w-full text-left flex flex-row items-center justify-between gap-3 rounded">
                                <x-icon name="heart" class="w-6! h-6! text-black dark:text-white {{ auth()->user() && auth()->user()->savedVideo->contains($video->id) ? 'fill-black dark:fill-white' : '' }}" />
                            </x-ui.button>
                        </form>
                    </div>
                </div>

                {{-- Category Tag Piils --}}
                <p class="mt-4 text-xl">Tag video:</p>
                <ul class="flex flex-row items-center gap-2 mt-2">
                    @foreach ($video->categories as $videoCategory)
                        <li>
                            <a 
                                href="{{ route('category.show', $videoCategory) }}"
                                class="py-2 px-2.5 bg-gray-100 text-black text-sm rounded-full hover:bg-primary hover:text-white dark:bg-white/10 dark:text-white dark:hover:bg-primary dark:hover:text-white">
                                {{ $videoCategory->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </article>

            {{-- Other Videos --}}
            {{-- Title --}}
            <p class="text-xl font-medium mb-2 inline-flex items-center gap-2">Video Lainnya</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($otherVideos as $otherVideo)
                    {{-- Video --}}
                    <x-video-card :video="$otherVideo"/>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        const videoSlug = "{{ $video->slug }}";

        let history = JSON.parse(localStorage.getItem('video_history')) || [];

        // Avoid Duplicate
        history = history.filter(id => id !== videoSlug);
        history.unshift(videoSlug);

        // Limit
        localStorage.setItem('video_history', JSON.stringify(history.slice(0,100)));
    </script>
</x-layouts.app>
