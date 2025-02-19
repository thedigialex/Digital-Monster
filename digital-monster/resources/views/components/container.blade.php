<div class="max-w-7xl mx-auto my-14 shadow-lg bg-secondary rounded-lg">
    @isset($header)
    <div class=" flex justify-center bg-accent space-x-4 px-2 pt-2 rounded-t-lg border-b-4 border-primary">
        {{ $header }}
    </div>
    @endisset
    {{ $slot }}
</div>