@props(['active'])

@php
    $classes = $active ?? false
    ? 'btn btn-outline-danger active w-100'
    : 'btn btn-outline-danger w-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
