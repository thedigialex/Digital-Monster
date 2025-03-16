@props(['icon' => 'fa-edit', 'label' => 'Edit'])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'relative rounded-md flex items-center border border-accent bg-accent overflow-hidden transition-all duration-300 group active:scale-95 w-[40px] md:w-[120px] h-[40px]']) }}>
    <span class="absolute text-secondary left-1/3 transform -translate-x-1/2 font-semibold transition-all duration-300 group-hover:opacity-0 md:block hidden">
        {{ $label }}
    </span>
    <span class="absolute text-accent right-0 w-[40px] h-full bg-secondary flex items-center justify-center transition-all duration-300 group-hover:w-full group-hover:bg-secondary">
        <i class="fa {{ $icon }}"></i>
    </span>
</button>