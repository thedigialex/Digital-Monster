@props([
    'type' => 'edit',
    'icon' => $type === 'delete' ? 'fa-trash' : 'fa-edit',
    'label' => $type === 'delete' ? 'Delete' : 'Edit',
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
<button {{ $attributes->merge(['type' => 'submit', 'class' => "relative rounded-md flex items-center overflow-hidden transition-all duration-300 group active:scale-95 h-[40px] border $buttonWidth $borderColor $bgColor"]) }}>
    @if(!$isCompact)
        <span class="label absolute left-1/3 text-secondary transform -translate-x-1/2 font-semibold transition-all duration-300 group-hover:opacity-0 md:block hidden">
            {{ $label }}
        </span>
    @endif
    <span class="absolute right-0 {{ $textColor }} {{ $iconWidth }} h-full bg-secondary flex items-center justify-center transition-all duration-300 group-hover:w-full group-hover:bg-secondary">
        <i class="icon fa {{ $icon }}"></i>
    </span>
</button>
