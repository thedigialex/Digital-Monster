@props(['class' => ''])

<div class="max-w-7xl mx-auto my-8 lg:my-14 shadow-lg shadow-secondary bg-primary rounded-md">
    @isset($header)
    <div class="bg-secondary p-4 rounded-t-md border-b-4 border-accent flex justify-between items-center">
        {{ $header }}
    </div>
    @endisset

    @isset($info)
    <div class="bg-secondary py-6 px-4 mb-4 rounded-b-md">
        {{ $info }}
    </div>
    @endisset

    <div class="{{ $class }}">
        {{ $slot }}
    </div>
</div>