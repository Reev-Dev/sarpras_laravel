@props(['active'])

@php
    $classes = $active ?? false
    ? 'btn btn-outline-success active w-100'
    : 'btn btn-outline-success w-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
