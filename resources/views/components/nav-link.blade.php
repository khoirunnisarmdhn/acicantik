@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-sm font-semibold text-custom border-r-4 border-custom transition duration-150 ease-in-out'
            : 'flex items-center px-4 py-3 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-custom hover-bg-custom border-r-4 border-transparent transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} 
   style="{{ ($active ?? false) ? 'background-color: color-mix(in srgb, var(--main-color), transparent 90%)' : '' }}">
    {{ $slot }}
</a>