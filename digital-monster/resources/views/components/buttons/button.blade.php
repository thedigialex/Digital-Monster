@props([
'type' => 'edit',
'icon' => $type === 'delete' ? 'fa-trash' : 'fa-edit',
'label' => $type === 'delete' ? 'Delete' : 'Edit',
'showSpinner' => false,
])

@php
$isDelete = $type === 'delete';
$borderColor = $isDelete ? 'border-error' : 'border-accent';
$bgColor = $isDelete ? 'bg-error' : 'bg-accent';
$textColor = $isDelete ? 'text-error' : 'text-accent';
$isCompact = empty($label);
$buttonWidth = $isCompact
? ($isDelete ? 'w-[40px]' : 'w-[60px]')
: ($isDelete ? 'w-[40px] md:w-[140px]' : 'w-[60px] md:w-[140px]');
$iconWidth = $isCompact
? ($isDelete ? 'w-[40px]' : 'w-[60px]')
: ($isDelete ? 'w-[40px]' : 'w-[60px] md:w-[40px]');
@endphp

@if($showSpinner)
<div x-data="{ loading: false }">
    <button
        @click="loading = true"
        x-bind:disabled="loading"
        {{ $attributes->merge([
                'type' => 'submit', 
                'class' => "relative rounded-md flex items-center overflow-hidden transition-all duration-300 group active:scale-95 h-[45px] border $buttonWidth $borderColor $bgColor"
            ]) }}>
        @if(!$isCompact)
        <span
            class="label absolute left-1/3 text-secondary transform -translate-x-1/2 font-semibold transition-all duration-300 group-hover:opacity-0 md:block hidden"
            x-show="!loading">
            {{ $label }}
        </span>
        @endif

        <span class="absolute right-0 {{ $textColor }} {{ $iconWidth }} h-full bg-secondary flex items-center justify-center transition-all duration-300 group-hover:w-full group-hover:bg-secondary">
            <template x-if="!loading">
                <i class="icon fa {{ $icon }}"></i>
            </template>
            <template x-if="loading">
                <svg class="animate-spin h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
                </svg>
            </template>
        </span>
    </button>
</div>
@else
<button
    {{ $attributes->merge([
            'type' => 'submit', 
            'class' => "relative rounded-md flex items-center overflow-hidden transition-all duration-300 group active:scale-95 h-[45px] border $buttonWidth $borderColor $bgColor"
        ]) }}>
    @if(!$isCompact)
    <span class="label absolute left-1/3 text-secondary transform -translate-x-1/2 font-semibold transition-all duration-300 group-hover:opacity-0 md:block hidden">
        {{ $label }}
    </span>
    @endif

    <span class="absolute right-0 {{ $textColor }} {{ $iconWidth }} h-full bg-secondary flex items-center justify-center transition-all duration-300 group-hover:w-full group-hover:bg-secondary">
        <i class="icon fa {{ $icon }}"></i>
    </span>
</button>
@endif