@props(['text'])

<button {{ $attributes->merge(['class' => 'w-16 h-16 flex items-center justify-center bg-accent text-text font-bold rounded-md shadow-md transition-all duration-200 transform hover:scale-95 hover:shadow-lg active:scale-90 active:shadow-sm active:border-accent active:bg-secondary']) }}>
    {{ $text }}
</button>