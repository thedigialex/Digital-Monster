@props(['divClasses' => '', 'name' => '', 'value' => '', 'type' => 'text'])

<div class="{{ $divClasses }}">
    <x-inputs.label for="{{ $name }}" class="pb-1">{{ ucwords($name) }}</x-inputs.label>
    <input
        autocomplete="off"
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        class="w-full text-text bg-neutral focus:border-accent focus:ring-accent rounded-md" />
</div>