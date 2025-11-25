<x-layouts.app>
    <section class="bg-gray-50 min-h-[calc(100dvh-130px)] py-6">
        {{-- Container --}}
        <div class="container mx-auto flex flex-col lg:flex-row gap-x-6">
            {{-- Sidebar --}}
            <div class="min-w-[22%] hidden lg:block">
                <x-profile-sidebar />
            </div>

            {{-- Content --}}
            <div class="flex-1">
                {{-- Personal Information --}}
                <div class="border rounded-lg w-full">
                    <p class="text-xl text-primary font-semibbold p-6 border-b">Post Video</p>

                    <form 
                        action={{ route('post.store') }}
                        method="POST"
                        enctype="multipart/form-data"
                        class="flex flex-col gap-6 p-6"
                    >
                        @csrf

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
                                />
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
                                />
                            </div>
                        </div>

                        {{-- Thumbnail --}}
                        <div class="grid gap-2">
                            <x-ui.label for="thumbnail" :required="true">Gambar Thumbnail</x-ui.label>

                            <p class="text-xs text-gray-500 mb-2">
                                Supported formats: JPEG, PNG, JPG, WEBP (max 20MB)
                            </p>
                            <div 
                                id="dropzone-thumbnail"
                                class="flex flex-col items-center justify-center w-full p-6 border border-dashed border-gray-300 rounded-lg cursor-pointer transition"
                                onclick="triggerFileInput('thumbnail')"
                                ondragover="dragOver(event, 'dropzone-thumbnail')"
                                ondragleave="dragLeave('dropzone-thumbnail')"
                                ondrop="dropFile(event, 'thumbnail', 'preview-thumbnail')"
                            >
                                <x-ui.button
                                    type="button"
                                    variant="outline"
                                    class="flex items-center gap-2 text-primary! border! border-primary! hover:bg-primary/5!"
                                >
                                    <x-icon name="upload" class="w-4 h-4"/>
                                    Drag or Click to Upload
                                </x-ui.button>
                            </div>

                            <input
                                id="thumbnail"
                                type="file"
                                name="thumbnail"
                                class="hidden"
                                accept="image/jpeg,image/png,image/webp"
                                onchange="showPreview(event, 'preview-thumbnail')"
                            />

                            <div class="mt-4 flex justify-center">
                                <img 
                                    id="preview-thumbnail"
                                    src=""
                                    class="h-60 aspect-video object-contain bg-gray-200 rounded-xl hidden"/>
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
                                    selected: [],
                                    all: @js($categories),
                                    get filtered() {
                                        return this.all.filter(c => 
                                            c.name.toLowerCase().includes(this.search.toLowerCase()) &&
                                            !this.selected.find(s => s.id === c.id)
                                        );
                                    },
                                    add(item) {
                                        // avoid duplicates
                                        if (!this.selected.find(s => s.id === item.id)) {
                                            this.selected.push(item);
                                        }
                                        this.search = '';
                                        // keep dropdown open (user requested)
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

                                <!-- Dropdown -->
                                <div 
                                    x-cloak
                                    x-show="open"
                                    x-transition
                                    class="absolute mt-1 w-full bg-white border rounded shadow z-50"
                                    style="display: none;"
                                >
                                    <template x-for="item in filtered" :key="item.id">
                                        <!-- use mousedown to run before blur; stop propagation to avoid outside click -->
                                        <div 
                                            @mousedown.prevent="add(item)"
                                            @click.stop
                                            class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
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
                                <option value="published" selected>Jadi Duit</option>
                                <option value="draft">Draf</option>
                            </x-ui.selectct>
                        </div>


                        {{-- Submit Button --}}
                        <x-ui.button 
                            type="submit" 
                            class="mt-2 font-semibold self-end px-8" 
                            tabIndex="2">
                            Ubah
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Thumbnail --}}
    <script>
        function triggerFileInput(id) {
            document.getElementById(id).click();
        }

        function showPreview(event, previewId) {
            const preview = document.getElementById(previewId);
            const file = event.target.files[0];

            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function dragOver(event, dropzoneId) {
            event.preventDefault();
            document.getElementById(dropzoneId).classList.add('border-primary', 'bg-primary/5');
        }

        function dragLeave(dropzoneId) {
            document.getElementById(dropzoneId).classList.remove('border-primary', 'bg-primary/5');
        }

        function dropFile(event, inputId, previewId) {
            event.preventDefault();

            dragLeave(inputId);

            const fileInput = document.getElementById(inputId);
            const file = event.dataTransfer.files[0];

            if (!file) return;

            // Assign file
            fileInput.files = event.dataTransfer.files;

            // Preview
            const preview = document.getElementById(previewId);
            const reader = new FileReader();

            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        }
    </script>

</x-layouts.app>
