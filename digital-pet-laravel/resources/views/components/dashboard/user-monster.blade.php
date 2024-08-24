<div
    class="bg-fill relative flex items-center justify-center bg-center p-8 bg-no-repeat h-[600px]"
    style="background-image: url('{{ asset('storage/items/frame.png') }}');">
    <div
        class="bg-cover relative flex items-center justify-center bg-center p-8 bg-no-repeat rounded h-[400px] w-[400px]"
        style="background-image: url('{{ asset('storage/' . $backgroundImage) }}');">
        @isset($spriteSheet)
        <img src="{{ asset('storage/' . $spriteSheet) }}" alt="Sprite Preview">
        @endif
    </div>
</div>