@props(['active'])

@php
    $classes = $active ?? false
    ? 'btn btn-outline-warning active w-100'
    : 'btn btn-outline-warning w-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
