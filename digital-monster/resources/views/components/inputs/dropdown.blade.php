@props(['label' => null, 'disabled' => false])

<div>
    @if ($label)
    <x-inputs.label for="{{ $attributes['id'] }}" class="pb-1">{{ $label }}</x-inputs.label>
    @endif
    <select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'text-text bg-neutral focus:border-accent focus:ring-accent rounded-md']) !!}>
        {{ $slot }}
    </select>
</div>