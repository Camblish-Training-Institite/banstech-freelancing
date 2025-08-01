@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full py-2 px-2 text-gray-900 bg-gray-500'
            : 'block w-full py-2 px-2 text-gray-200 bg-black hover:text-gray-500 hover:bg-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
