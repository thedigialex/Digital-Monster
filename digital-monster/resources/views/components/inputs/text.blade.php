@props(['divClasses' => '', 'name' => '', 'value' => '', 'type' => 'text'])

<div id="{{ $name }}_div" class="{{ $divClasses }}">
    <x-inputs.label for="{{ $name }}" class="pb-1">{{ ucwords(str_replace('_', ' ', $name)) }}</x-inputs.label>
    <input
        autocomplete="off"
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        class="w-full text-text bg-neutral focus:border-accent focus:ring-accent rounded-md
        @error($name) border-error @enderror" />
    @error($name)
    <p class="text-error text-sm mt-1">Field is required</p>
    @enderror
</div>