<button {{ $attributes->merge(['type' => 'button', 'class' => 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md']) }}>
    {{ $slot }}
</button>