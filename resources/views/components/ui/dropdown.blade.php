<div x-data="{ open: false }" class="relative">
    {{-- Trigger --}}
    <button 
        type="button"
        @click="open = !open"
        @click.outside="open = false"
        class="inline-flex items-center justify-center"
    >
        {{ $trigger }}
    </button>

    {{-- Menu --}}
    <div 
        x-show="open"
        x-transition
        class="absolute right-0 mt-2 w-40 rounded-md border bg-white shadow-md py-1 z-50"
    >
        {{ $slot }}
    </div>
</div>
