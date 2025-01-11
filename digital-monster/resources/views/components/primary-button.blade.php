<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-accent rounded-md font-semibold text-xs text-text uppercase tracking-widest hover:text-secondary hover:bg-accent_light focus:bg-accent active:bg-accent_light']) }}>
    {{ $slot }}
</button>
