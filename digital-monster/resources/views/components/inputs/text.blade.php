@props(['disabled' => false])

<input 
    autocomplete="off" 
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge(['class' => 'text-text bg-neutral focus:border-accent focus:ring-accent rounded-md ']) !!}>
