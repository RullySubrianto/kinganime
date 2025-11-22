<x-layouts.app>
    <section class="bg-gray-50 flex flex-col justify-center items-center">
        {{-- Container --}}
        <div class="container space-y-6 py-6">
            {{-- Tabs --}}
            <div class="self-start flex flex-row overflow-x-auto whitespace-nowrap scrollbar-hide gap-4">
                {{-- Tab --}}
                <a class="py-2.5 px-5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white">Film Action</a>
                <a class="py-2.5 px-5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white">Film Horror</a>
                <a class="py-2.5 px-5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white">Film Trailer</a>
                <a class="py-2.5 px-5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white">Film Gore</a>
                <a class="py-2.5 px-5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white">Film Romance</a>
            </div>

            {{-- Videos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($videos as $video)
                    {{-- Video --}}
                    <x-video-card :video="$video"/>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
