@props(['label' => 'Edit', 'id' => ''])

<button id="{{ $id }}" {{ $attributes->merge(['class' => 'py-2 font-semibold rounded-t-md hover:bg-accent']) }}>
    {{ $label }}
</button>