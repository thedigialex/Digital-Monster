<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-error rounded-md font-semibold text-xs text-text uppercase tracking-widest hover:bg-red-500 hover:text-secondary']) }}>
    {{ $slot }}
</button>
