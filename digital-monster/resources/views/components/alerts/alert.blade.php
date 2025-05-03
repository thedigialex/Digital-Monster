@props([
    'type' => 'Info',
    'message' => 'Please fix fields.',
])
@php
    $typeClasses = [
        'Success' => 'bg-success text-secondary',
        'Error' => 'bg-error text-text',
        'Info' => 'bg-info text-secondary',
    ];
    $title = $type;
    $classes = $typeClasses[$type];
@endphp

<div class="{{ $classes }} px-4 py-3 rounded-b" role="alert">
    <strong class="font-bold">{{ $title }}</strong>
    <span class="block sm:inline">
        {{ $message }}
    </span>
</div>
