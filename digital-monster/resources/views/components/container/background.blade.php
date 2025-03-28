<div style="background-image: url('{{ asset($background) }}');" {{ $attributes->merge(['class' => 'flex flex-col justify-center items-center bg-cover bg-center h-[60vh]']) }}>
    {{ $slot }}
</div>
