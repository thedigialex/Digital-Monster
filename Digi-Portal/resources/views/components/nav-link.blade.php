@props(['active'])

@php
$baseClasses = 'relative inline-flex items-center p-1 w-full font-medium transition duration-150 ease-in-out focus:outline-none';
$activeClasses = 'text-accent after:w-full after:bg-accent after:scale-x-100';
$inactiveClasses = 'text-text hover:text-accent after:w-0 after:bg-accent hover:after:w-full focus:text-accent focus:after:bg-accent focus:after:w-full';
$afterClasses = 'after:absolute after:bottom-0 after:left-0 after:h-[2px] after:transition-all after:duration-300 after:origin-left';

$classes = $active ? "$baseClasses $activeClasses $afterClasses" : "$baseClasses $inactiveClasses $afterClasses";
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <span class="space-x-2 ml-4">
        {{ $slot }}
    </span>
</a>
