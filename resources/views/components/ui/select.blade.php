<select
    {{ $attributes->merge([
        'class' => 'border h-9 py-1 px-3 rounded-md shadow-xs text-sm focus:ring-primary outline-none'
    ]) }}
>
    {{ $slot }}
</select>
