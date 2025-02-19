<h3 {{ $attributes->class([ 'text-xl font-semibold', 'text-accent' => !isset($attributes['class']),]) }}>
    {{ $slot }}
</h3>
