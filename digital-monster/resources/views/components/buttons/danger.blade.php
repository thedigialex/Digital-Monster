@props(['icon' => 'fa-trash', 'label' => 'Delete'])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'relative rounded-md flex items-center border border-error bg-error overflow-hidden transition-all duration-300 group active:scale-95 w-[40px] md:w-[120px] h-[40px]']) }}>
    <span class="absolute left-1/3 text-secondary transform -translate-x-1/2 font-semibold transition-all duration-300 group-hover:opacity-0 md:block hidden">
        {{ $label }}
    </span>
    <span class="absolute right-0 text-error w-[40px] h-full bg-secondary flex items-center justify-center transition-all duration-300 group-hover:w-full group-hover:bg-secondary">
        <i class="fa {{ $icon }}"></i>
    </span>
</button>