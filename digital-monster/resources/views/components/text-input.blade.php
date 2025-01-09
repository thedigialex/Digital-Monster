@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'text-text bg-neutral focus:border-accent focus:ring-accent rounded-md ']) !!}>
