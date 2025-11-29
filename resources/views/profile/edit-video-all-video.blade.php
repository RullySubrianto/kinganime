<x-layouts.profile title="Edit Video">
    {{-- Personal Information --}}
    <div class="border rounded-lg w-full">
        <p class="text-xl text-primary font-semibbold p-6 border-b">Edit Video</p>

        <form 
            action={{ route('all-video.update', $video->slug) }}
            method="POST"
            enctype="multipart/form-data"
            class="flex flex-col gap-6 p-6"
        >
            @csrf
            @method('PUT')

            {{-- Title & External Link --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Title --}}
                <div class="grid gap-2">
                    <x-ui.label for="title" :required="true">Judul Video</x-ui.label>
                    <x-ui.input
                        id="title"
                        type="text"
                        required
                        tabIndex="1"
                        autoComplete="title"
                        name="title"
                        placeholder="Judul Video"
                        value="{{ $video->title }}"
                    />

                    <x-error-inline-input :value="'title'"/>
                </div>

                {{-- External Link --}}
                <div class="grid gap-2">
                    <x-ui.label for="external_link" :required="true">Link Video</x-ui.label>
                    <x-ui.input
                        id="external_link"
                        type="text"
                        required
                        tabIndex="2"
                        autoComplete="external_link"
                        name="external_link"
                        placeholder="https://videy.co/1.mp4"
                        value="{{ $video->external_link }}"
                    />

                    <x-error-inline-input :value="'external_link'"/>
                </div>
            </div>

            {{-- Thumbnail --}}
            <div class="grid gap-2">
                <x-ui.label for="thumbnail" :required="true">Gambar Thumbnail</x-ui.label>

                <p class="text-xs text-gray-500 mb-2">
                    Supported formats: JPEG, PNG, JPG, WEBP (max 20MB)
                </p>

                {{-- Upload Box --}}
                <div 
                    id="dropZone"
                    class="flex flex-col items-center justify-center w-full p-6 border border-dashed border-gray-300 rounded-lg cursor-pointer transition"
                    onClick="handleThumbnailClick()"
                    ondragover="handleDragOver(event)"
                    ondragleave="handleDragLeave(event)"
                    ondrop="handleDrop(event)"
                >
                    <x-ui.button
                        type="button"
                        variant="outline"
                        class="flex items-center gap-2 text-primary! border! border-primary! bg-transparent! hover:bg-primary/5!"
                        >
                        <x-icon name="upload" class="w-4 h-4" />
                        Drag or Click to Upload
                    </x-ui.button>
                </div>


                {{-- Hidden Input --}}
                <input
                    id="thumbnail"
                    type="file"
                    name="thumbnail"
                    class="hidden"
                    onchange="displaySelectedImage(event, 'selectedThumbnail')"
                    accept="image/jpeg,image/png,image/webp"
                />

                <x-error-inline-input :value="'thumbnail'"/>

                {{-- Image Preview --}}
                <div class="mb-4 flex justify-center">
                    <img 
                        id="selectedThumbnail" 
                        src="{{ $video->thumbnail ? asset($video->thumbnail) : '' }}"
                        alt="Thumbnail" 
                        class="h-60 aspect-video object-contain bg-gray-200 rounded-xl" 
                        style="{{ $video->thumbnail ? '' : 'display:none;' }}"/>
                </div>
            </div>

            <style>
                /* ensures x-cloak hidden before alpine mounts */
                [x-cloak] { display: none !important; }
            </style>

            {{-- Category --}}
            <div class="grid gap-2">
                <x-ui.label for="categories" :required="true">Kategori</x-ui.label>

                <div 
                    x-data="{
                        open: false,
                        search: '',
                        selected: @js($video->categories),
                        all: @js($categories),
                        get filtered() {
                            return this.all.filter(c => 
                                c.name.toLowerCase().includes(this.search.toLowerCase()) &&
                                !this.selected.find(s => s.id === c.id)
                            );
                        },
                        add(item) {
                            if (!this.selected.find(s => s.id === item.id)) {
                                this.selected.push(item);
                            }
                            this.search = '';
                            this.$nextTick(() => { this.$refs.input.focus(); });
                        },
                        remove(id) {
                            this.selected = this.selected.filter(i => i.id !== id);
                            this.$nextTick(() => { this.$refs.input.focus(); });
                        }
                    }"
                    class="relative w-full"
                    @click.outside="open = false"
                >


                    <!-- Tag Pills -->
                    <div class="flex flex-wrap gap-2 mb-2">
                        <template x-for="item in selected" :key="item.id">
                            <span 
                                class="px-4 py-1 bg-blue-100 hover:bg-primary hover:text-white text-blue-700 rounded-full text-sm flex items-center gap-2 cursor-pointer"
                                @click.stop="remove(item.id)">
                                <span x-text="item.name"></span>
                                <span>×</span>
                                <input type="hidden" name="categories[]" :value="item.id">
                            </span>
                        </template>
                    </div>

                    <!-- Input -->
                    <input
                        x-ref="input"
                        @focus="open = true"
                        @keydown.escape.prevent="open = false"
                        x-model="search"
                        type="text"
                        placeholder="Search categories..."
                        class="w-full border py-1 px-3 h-9 rounded text-sm shadow-xs"
                    >

                    <x-error-inline-input :value="'categories'"/>

                    <!-- Dropdown -->
                    <div 
                        x-cloak
                        x-show="open"
                        x-transition
                        class="absolute mt-1 w-full bg-white dark:bg-neutral-900 border rounded shadow z-50"
                        style="display: none;"
                    >
                        <template x-for="item in filtered" :key="item.id">
                            <!-- use mousedown to run before blur; stop propagation to avoid outside click -->
                            <div 
                                @mousedown.prevent="add(item)"
                                @click.stop
                                class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer"
                            >
                                <span x-text="item.name"></span>
                            </div>
                        </template>

                        <div x-show="filtered.length === 0" class="p-2 text-gray-500 text-sm">
                            No categories found
                        </div>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="grid gap-2 ">
                <x-ui.label for="status" :required="true">Status</x-ui.label>

                <x-ui.select 
                    name="status" 
                    id="status"
                    required
                    tabindex="3">
                    <option value="published" @selected($video->status === 'published')>Jadi Duit</option>
                    <option value="draft" @selected($video->status === 'draft')>Draf</option>
                </x-ui.select>

                <x-error-inline-input :value="'status'"/>
            </div>


            {{-- Submit Button --}}
            <x-ui.button 
                type="submit" 
                class="mt-2 font-semibold self-end px-8 cursor-pointer" 
                tabIndex="2">
                Ubah
            </x-ui.button>
        </form>
    </div>

    {{-- Thumbnail --}}
    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('thumbnail');
        const selectedImage = document.getElementById('selectedThumbnail');
        const originalThumbnail = selectedImage.src;

        function handleThumbnailClick() {
            fileInput.click();
        }

        function displaySelectedImage(event, elementId = 'selectedThumbnail') {
            const img = document.getElementById(elementId);

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    img.style.display = "block";
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        }

        // DRAG & DROP
        function handleDragOver(e) {
            e.preventDefault();
            dropZone.classList.add('border-primary', 'bg-primary/5');
        }

        function handleDragLeave() {
            dropZone.classList.remove('border-primary', 'bg-primary/5');
        }

        function handleDrop(e) {
            e.preventDefault();
            dropZone.classList.remove('border-primary', 'bg-primary/5');

            const file = e.dataTransfer.files[0];
            if (!file) return;

            // Assign file to input programmatically
            fileInput.files = e.dataTransfer.files;

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                selectedImage.src = e.target.result;
                selectedImage.style.display = "block";
            };
            reader.readAsDataURL(file);
        }
    </script>
</x-layouts.profile>
