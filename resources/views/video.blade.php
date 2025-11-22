<x-layouts.app>
    <section class="bg-gray-50 flex flex-col justify-center items-center">
        {{-- Container --}}
        <div class="container py-6">
            {{-- Video --}}
            <div class="mb-6">
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

                {{-- Title --}}
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
                            action={{ route('video.save.watchlist', $video->id) }}
                            method="POST">
                            @csrf

                            <x-ui.button 
                                type="submit"
                                variant="link"
                                size="icon"
                                class="w-full text-left flex flex-row items-center justify-between gap-3 rounded">
                                <x-icon name="watchlist" class="w-6! h-6! text-black"/>
                            </x-ui.button>
                        </form>

                        {{-- Save Button --}}
                        <form 
                            action={{ route('video.save', $video->id) }}
                            method="POST">
                            @csrf

                            <x-ui.button 
                                type="submit"
                                variant="link"
                                size="icon"
                                class="w-full text-left flex flex-row items-center justify-between gap-3 rounded">
                                <x-icon name="heart" class="w-6! h-6! text-black {{ auth()->user() && auth()->user()->savedVideo->contains($video->id) ? 'fill-black' : '' }}" />
                            </x-ui.button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Other Videos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($otherVideos as $video)
                    {{-- Video --}}
                    <x-video-card :video="$video"/>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
