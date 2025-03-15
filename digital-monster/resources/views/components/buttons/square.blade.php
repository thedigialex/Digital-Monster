@props(['text'])

<button {{ $attributes->merge(['class' => 'w-24 h-16 flex items-center justify-center bg-accent text-text font-bold rounded shadow-md transition-all duration-200 hover:bg-primary active:scale-95']) }}>
    {{ $text }}
</button>