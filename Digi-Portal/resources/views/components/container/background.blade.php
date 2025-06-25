<div {{ $attributes->merge(['class' => 'relative w-full rounded-b-md shadow-lg h-[60vh] overflow-hidden']) }}>
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset($background) }}');"></div>
    <div class="absolute inset-0 pointer-events-none z-10 {{ $timeOfDay }}"></div>
    <div class="relative z-20 w-full h-full flex flex-col justify-center items-center gap-4">
        {{ $slot }}
    </div>
</div>