<button {{ $attributes->merge(['class' => 'p-4 bg-secondary text-text hover:text-accent rounded-md']) }}>
    <i class="fa-solid {{ $direction == 'left' ? 'fa-chevron-left' : 'fa-chevron-right' }}"></i>
</button>