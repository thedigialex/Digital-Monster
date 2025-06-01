@props(['icon' => null])

<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 bg-secondary rounded-md font-semibold text-xs text-accent uppercase border-2 border-secondary hover:text-secondary hover:bg-accent active:bg-accent']) }}>
    {{ $slot }}
    @if ($icon)
    <i class="fa {{ $icon }} ml-2"></i>
    @endif
</button>