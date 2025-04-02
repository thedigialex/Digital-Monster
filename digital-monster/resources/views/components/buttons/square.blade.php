@props(['text', 'icon'=>"fa-check"])
<button {{ $attributes->merge(['class' => 'w-16 h-16 w-[150px] flex items-center justify-center bg-accent text-text font-bold rounded-md shadow-md transition-all duration-200 transform hover:scale-95 hover:bg-secondary hover:text-accent hover:shadow-lg active:scale-90 active:shadow-sm active:border-accent active:bg-secondary']) }}>
    <i class="fa {{ $icon }} mr-2"></i>
    {{ $text }}
</button>
