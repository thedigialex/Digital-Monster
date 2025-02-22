@props(['icon' => null])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 bg-accent rounded-md font-semibold text-xs text-text uppercase hover:text-secondary hover:bg-accent_light focus:bg-accent active:bg-accent_light']) }}>
    {{ $slot }}
    @if ($icon)
    <i class="fa {{ $icon }} ml-2"></i>
    @endif
</button>