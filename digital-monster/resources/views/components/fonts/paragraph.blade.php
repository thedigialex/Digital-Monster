<p {{ $attributes->class(['text-text' => !isset($attributes['class'])]) }}>
    {{ $slot }}
</p>
