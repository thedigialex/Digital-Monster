@props(['icon' => 'fa-trash', 'label' => 'Delete'])

<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 bg-error rounded-md font-semibold text-xs text-text uppercase 
        border-2 border-error hover:text-error hover:bg-primary active:bg-primary focus:bg-error focus:border-error']) }}>
    {{ $label }}
    <i class="fa {{ $icon }} ml-2"></i>
</button>