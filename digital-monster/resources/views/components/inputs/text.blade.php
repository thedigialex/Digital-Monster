@props(['divClasses' => '', 'name' => '', 'value' => '', 'type' => 'text', 'messages' => ''])

<div id="{{ $name }}_div" class="{{ $divClasses }} mt-4">
    <x-inputs.label for="{{ $name }}" class="pb-1">{{ ucwords(str_replace('_', ' ', $name)) }}</x-inputs.label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        class="w-full text-text bg-neutral focus:border-accent focus:ring-accent rounded-md
        @error($name) border-error @enderror" />

    @if ($messages && is_array($messages) && count($messages) > 0)
    <p class="text-error text-sm mt-1">{{ implode(', ', $messages) }}</p>
    @endif
</div>