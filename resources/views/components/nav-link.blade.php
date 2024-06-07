@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-warning text-sm font-medium leading-5 text-primary-70 focus:outline-none focus:border-warning-70 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-primary-30 hover:text-primary-50 hover:border-primary-10 focus:outline-none focus:text-primary-50 focus:border-primary-10 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
