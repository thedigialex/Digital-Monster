<x-fonts.paragraph :id="$id ?? ''" {{ $attributes->merge(['class' => 'text-text p-2 bg-primary rounded-md']) }}>
    {{ $slot }}
</x-fonts.paragraph>
