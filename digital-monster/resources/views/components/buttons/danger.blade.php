@props(['icon' => 'fa-trash', 'label' => 'Delete'])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'relative text-text w-[120px] h-[40px] rounded-md flex items-center border border-error bg-error overflow-hidden transition-all duration-300 group active:scale-95 hover:text-error']) }}>
    <span class="absolute left-1/3 transform -translate-x-1/2 font-semibold transition-all duration-300 group-hover:opacity-0">
        {{ $label }}
    </span>
    <span class="absolute right-0 w-[40px] h-full bg-secondary flex items-center justify-center transition-all duration-300 group-hover:w-full group-hover:bg-secondary">
        <i class="fa {{ $icon }}"></i>
    </span>
</button>