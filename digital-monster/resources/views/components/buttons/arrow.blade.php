<button {{ $attributes->merge(['class' => 'p-4 bg-secondary text-text hover:text-accent rounded-md transition-all duration-200 transform hover:scale-95 hover:shadow-lg active:scale-90 active:shadow-sm']) }}>
    <i class="fa-solid {{ $direction == 'left' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
</button>