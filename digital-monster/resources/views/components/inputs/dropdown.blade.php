@props(['divClasses' => '', 'name' => '', 'onchange' => ''])
<div class="{{ $divClasses }}">
    <x-inputs.label for="{{ $name }}" class="pb-1">{{ ucwords($name) }}</x-inputs.label>
    <select name="{{ $name }}" id="{{ $name }}" onchange="{{ $onchange }}" class="w-full text-text bg-neutral focus:border-accent focus:ring-accent rounded-md">
        {{ $slot }}
    </select>
</div>