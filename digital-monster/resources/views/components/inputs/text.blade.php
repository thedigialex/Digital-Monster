@props([
'name' => '',
'value' => '',
'type' => 'text',
'messages' => '',
'id' => null,
])

@php
$id = $id ?? $name;
$autocompleteDefaults = [
'email' => 'email',
'password' => 'current-password',
'new_password' => 'new-password',
'username' => 'username',
'name' => 'name',
];
$autocomplete = $autocompleteDefaults[$name] ?? 'on';
@endphp

<div id="{{ $id }}_div" {{ $attributes->merge(['class' => 'mt-4']) }}>
    <x-inputs.label for="{{ $id }}" class="pb-1">{{ ucwords(str_replace('_', ' ', $name)) }}</x-inputs.label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $value }}"
        autocomplete="{{ $autocomplete }}"
        class="w-full text-text bg-neutral focus:border-accent focus:ring-accent rounded-md
        @error($name) border-error @enderror" />

    @if ($messages && is_array($messages) && count($messages) > 0)
    <p class="text-error text-sm mt-1">{{ implode(', ', $messages) }}</p>
    @endif
</div>