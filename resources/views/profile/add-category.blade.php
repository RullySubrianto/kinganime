<x-layouts.profile title="Tambah Kategori">
    {{-- Add Category --}}
    <div class="border rounded-lg w-full">
        <p class="text-xl text-primary dark:text-white font-semibbold p-6 border-b">Tambah Kategori</p>

        <form 
            action={{ route('category.store') }}
            method="POST"
            class="flex flex-col gap-6 p-6"
        >
            @csrf

            {{-- Name --}}
            <div class="grid gap-2">
                <x-ui.label for="name" :required="true">Nama</x-ui.label>
                <x-ui.input
                    id="name"
                    name="name"
                    type="text"
                    required
                    tabIndex="1"
                    autoComplete="categoryName"
                    placeholder="Nama"
                    value="{{ old('name') }}"
                />

                <x-error-inline-input :value="'name'"/>
            </div>
            
            <style>
                /* ensures x-cloak hidden before alpine mounts */
                [x-cloak] { display: none !important; }
            </style>

            {{-- Videos --}}
            <div class="grid gap-2">
                <x-ui.label for="videos" :required="true">Video</x-ui.label>

                <div 
                    x-data="{
                        open: false,
                        search: '',
                        selected: [],
                        all: @js($videos),
                        get filtered() {
                            return this.all.filter(c => 
                                c.title.toLowerCase().includes(this.search.toLowerCase()) &&
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
                                <span x-text="item.title"></span>
                                <span>×</span>
                                <input type="hidden" name="videos[]" :value="item.id">
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
                        placeholder="Cari video..."
                        tabindex="2"
                        class="w-full border py-1 px-3 h-9 rounded text-sm shadow-xs"
                    >

                    <x-error-inline-input :value="'videos'"/>

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
                                <span x-text="item.title"></span>
                            </div>
                        </template>

                        <div x-show="filtered.length === 0" class="p-2 text-gray-500 text-sm">
                            Video tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <x-ui.button 
                type="submit" 
                class="mt-2 font-semibold self-end px-8 cursor-pointer" 
                tabIndex="3">
                Tambah
            </x-ui.button>
        </form>
    </div>
</x-layouts.profile>
