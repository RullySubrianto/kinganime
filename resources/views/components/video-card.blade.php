@props(['video'])

{{-- Video --}}
<div>
    {{-- Thumbnail --}}
    <a href={{ route('video.show', $video->id) }}>
        <img 
            src={{ $video->thumbnail }} 
            alt="video"
            class="h-60 w-full aspect-video object-contain bg-[#d9d9d9] rounded-xl">
    </a>

    {{-- Title & Menu --}}
    <div class="flex justify-between mt-4 px-2">
        {{-- Title --}}
        <div>
            <a href={{ route('video.show', $video->id) }} class="hover:underline">
                <h1 class="font-medium line-clamp-1">{{ $video->title }}</h1>
            </a>
            <p class="text-sm">{{ \Illuminate\Support\Number::abbreviate($video->views_count) }} Views</p>
        </div>

        {{-- Menu --}}
        <div x-data="{ open: false }" class="relative">
            {{-- Trigger --}}
            <button
                type="button"
                @click="open = !open"
                @keydown.escape.window="open = false"
                class="inline-flex items-center justify-center p-2 rounded-full"
                aria-haspopup="true"
                :aria-expanded="open ? 'true' : 'false'">
                <x-icon name="three-dots-horizontal" class="w-4 h-4 rotate-90"/>
            </button>

            {{-- Panel --}}
            <div
                x-cloak
                x-show="open"
                x-transition.origin.top.right
                @click.outside="open = false"
                class="absolute right-0 mt-2 w-auto rounded-md shadow-lg bg-white ring-1 ring-black/5 z-50">
                    <div class="p-2 text-sm flex flex-col w-max min-w-fit">
                        {{-- Save Button --}}
                        <form 
                            action={{ route('video.save', $video->id) }}
                            method="POST">
                            @csrf

                            <button 
                                type="submit"
                                class="w-full text-left flex flex-row items-center justify-between gap-3 px-3 py-2 hover:bg-gray-50 rounded">
                                Tambah ke favorit
                                    <x-icon name="heart" class="w-4 h-4 {{ auth()->user() && auth()->user()->savedVideo->contains($video->id) ? 'fill-black text-black' : '' }}" />
                            </button>
                        </form>

                        {{-- Save To Watchlist Button --}}
                        <form 
                            action={{ route('video.save.watchlist', $video->id) }}
                            method="POST">
                            @csrf

                            <button 
                                type="submit"
                                class="w-full text-left flex flex-row items-center justify-between gap-3 px-3 py-2 hover:bg-gray-50 rounded">
                                Tambah ke wacthlist
                                <x-icon name="watchlist" class="w-4 h-4"/>
                            </button>
                        </form>
                    </div>
            </div>
        </div>

        {{-- Save Button --}}
        {{-- <form 
            action={{ route('video.save', $video->id) }}
            method="POST">
            @csrf

            <x-ui.button
                type="submit"
                variant="link"
                size="icon">
                <x-iconpark-like-o class="w-5! h-5!"/>
            </x-ui.button>
        </form> --}}
    </div>
</div>