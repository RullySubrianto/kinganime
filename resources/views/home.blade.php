<x-layouts.app title="Beranda">
    <section class="flex flex-col items-center min-h-[calc(100dvh-130px)]">
        {{-- Container --}}
        <div class="container space-y-6 py-6">
            
            {{-- Popular Category --}}
            <div class="mb-5 flex flex-row items-center justify-between">
                <p class="text-2xl font-medium ">Kategori Populer</p>

                {{-- History & Dark / Light Mode (Mobile) --}}
                <div class="flex lg:hidden flex-row items-center gap-2">
                    <a 
                        href="{{ route('history.index') }}"
                        class="p-2.5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white dark:bg-white/10 dark:text-white dark:hover:bg-primary dark:hover:text-white">
                        <x-icon name="history" class="w-5 h-5"/>
                    </a>

                    {{-- Dark / Light Toggle (Circle Button) --}}
                    <button 
                        x-data 
                        @click="
                            dark = !dark;
                            localStorage.setItem('dark', dark);
                            document.documentElement.classList.toggle('dark', dark);
                        "
                        class="w-10 h-10 flex items-center justify-center rounded-full
                            bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-yellow-300
                            transition-all duration-300 cursor-pointer hover:scale-105"
                    >
                        {{-- Sun Icon --}}
                        <span x-show="!dark" x-cloak>
                            <svg x-show="!dark" viewBox="0 0 24 24" class="w-4 h-4 text-yellow-500" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12Z" fill="currentcolor"></path> 
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M12 2C12.5523 2 13 2.44772 13 3V5C13 5.55228 12.5523 6 12 6C11.4477 6 11 5.55228 11 5V3C11 2.44772 11.4477 2 12 2Z" fill="currentcolor"></path> 
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M19.7071 4.29289C20.0976 4.68342 20.0976 5.31658 19.7071 5.70711L17.7071 7.70711C17.3166 8.09763 16.6834 8.09763 16.2929 7.70711C15.9024 7.31658 15.9024 6.68342 16.2929 6.29289L18.2929 4.29289C18.6834 3.90237 19.3166 3.90237 19.7071 4.29289Z" fill="currentcolor"></path> 
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M18 12C18 11.4477 18.4477 11 19 11H21C21.5523 11 22 11.4477 22 12C22 12.5523 21.5523 13 21 13H19C18.4477 13 18 12.5523 18 12Z" fill="currentcolor"></path> 
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M16.2929 16.2929C16.6834 15.9024 17.3166 15.9024 17.7071 16.2929L19.7071 18.2929C20.0976 18.6834 20.0976 19.3166 19.7071 19.7071C19.3166 20.0976 18.6834 20.0976 18.2929 19.7071L16.2929 17.7071C15.9024 17.3166 15.9024 16.6834 16.2929 16.2929Z" fill="currentcolor"></path> 
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M12 18C12.5523 18 13 18.4477 13 19V21C13 21.5523 12.5523 22 12 22C11.4477 22 11 21.5523 11 21V19C11 18.4477 11.4477 18 12 18Z" fill="currentcolor"></path> 
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M7.70711 16.2929C8.09763 16.6834 8.09763 17.3166 7.70711 17.7071L5.70711 19.7071C5.31658 20.0976 4.68342 20.0976 4.29289 19.7071C3.90237 19.3166 3.90237 18.6834 4.29289 18.2929L6.29289 16.2929C6.68342 15.9024 7.31658 15.9024 7.70711 16.2929Z" fill="currentcolor"></path> 
                                <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M2 12C2 11.4477 2.44772 11 3 11H5C5.55228 11 6 11.4477 6 12C6 12.5523 5.55228 13 5 13H3C2.44772 13 2 12.5523 2 12Z" fill="currentcolor"></path> <path fill-rule="evenodd" currentcolorlip-rule="evenodd" d="M4.29289 4.29289C4.68342 3.90237 5.31658 3.90237 5.70711 4.29289L7.70711 6.29289C8.09763 6.68342 8.09763 7.31658 7.70711 7.70711C7.31658 8.09763 6.68342 8.09763 6.29289 7.70711L4.29289 5.70711C3.90237 5.31658 3.90237 4.68342 4.29289 4.29289Z" fill="currentcolor"></path> 
                            </svg>
                        </span>

                        {{-- Moon Icon --}}
                        <span x-show="dark" x-cloak>
                            <svg x-show="dark" class="w-4 h-4 text-yellow-300" fill="currentColor" viewBox="0 0 17 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                        </span>
                    </button>

                </div>
            </div>

            {{-- Tabs --}}
            <div class="self-start flex flex-row overflow-x-auto whitespace-nowrap no-scrollbar gap-4">

                {{-- Tab --}}
                @foreach ($categories as $category)
                    <a 
                        href="{{ route('category.show', $category) }}"
                        class="py-2.5 px-5 bg-gray-100 text-black rounded-full hover:bg-primary hover:text-white dark:bg-white/10 dark:text-white dark:hover:bg-primary dark:hover:text-white">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            {{-- New Videos --}}
            <p class="inline-flex flex-row items-center gap-2 mb-5">
                <span class="text-2xl font-medium">Video Terbaru</span>
                <svg viewBox="-33 0 255 255" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" fill="#000000">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier"> 
                    <defs> <style> .cls-3 { fill: url(#linear-gradient-1); } .cls-4 { fill: #fc9502; } .cls-5 { fill: #fce202; } </style> 
                    <linearGradient id="linear-gradient-1" gradientUnits="userSpaceOnUse" x1="94.141" y1="255" x2="94.141" y2="0.188"> <stop offset="0" stop-color="#ff4c0d"></stop> <stop offset="1" stop-color="#fc9502"></stop> 
                    </linearGradient> </defs> <g id="fire"> <path d="M187.899,164.809 C185.803,214.868 144.574,254.812 94.000,254.812 C42.085,254.812 -0.000,211.312 -0.000,160.812 C-0.000,154.062 -0.121,140.572 10.000,117.812 C16.057,104.191 19.856,95.634 22.000,87.812 C23.178,83.513 25.469,76.683 32.000,87.812 C35.851,94.374 36.000,103.812 36.000,103.812 C36.000,103.812 50.328,92.817 60.000,71.812 C74.179,41.019 62.866,22.612 59.000,9.812 C57.662,5.384 56.822,-2.574 66.000,0.812 C75.352,4.263 100.076,21.570 113.000,39.812 C131.445,65.847 138.000,90.812 138.000,90.812 C138.000,90.812 143.906,83.482 146.000,75.812 C148.365,67.151 148.400,58.573 155.999,67.813 C163.226,76.600 173.959,93.113 180.000,108.812 C190.969,137.321 187.899,164.809 187.899,164.809 Z" id="path-1" class="cls-3" fill-rule="evenodd"></path> 
                    <path d="M94.000,254.812 C58.101,254.812 29.000,225.711 29.000,189.812 C29.000,168.151 37.729,155.000 55.896,137.166 C67.528,125.747 78.415,111.722 83.042,102.172 C83.953,100.292 86.026,90.495 94.019,101.966 C98.212,107.982 104.785,118.681 109.000,127.812 C116.266,143.555 118.000,158.812 118.000,158.812 C118.000,158.812 125.121,154.616 130.000,143.812 C131.573,140.330 134.753,127.148 143.643,140.328 C150.166,150.000 159.127,167.390 159.000,189.812 C159.000,225.711 129.898,254.812 94.000,254.812 Z" id="path-2" class="cls-4" fill-rule="evenodd"></path> 
                    <path d="M95.000,183.812 C104.250,183.812 104.250,200.941 116.000,223.812 C123.824,239.041 112.121,254.812 95.000,254.812 C77.879,254.812 69.000,240.933 69.000,223.812 C69.000,206.692 85.750,183.812 95.000,183.812 Z" id="path-3" class="cls-5" fill-rule="evenodd"></path> 
                </svg>
            </p>

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
