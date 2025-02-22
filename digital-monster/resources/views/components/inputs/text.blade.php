@props(['label' => null, 'disabled' => false])

<div >
    @if ($label)
    <x-inputs.label for="{{ $attributes['id'] }}" class="pb-1">{{ $label }}</x-inputs.label>
    @endif
    <input
        autocomplete="off"
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => 'text-text bg-neutral focus:border-accent focus:ring-accent rounded-md ']) !!}>
</div>