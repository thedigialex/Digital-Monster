@props(['id', 'rotate' => false, 'name' => ''])

<div class="flex flex-col gap-0">
    <x-fonts.paragraph class="text-primary font-bold" id="{{ $id }}-name"></x-fonts.paragraph>
    <div class="w-16 h-16 p-2">
        <div id="{{ $id }}" class="w-full h-full" style="{{ $rotate ? 'transform: scaleX(-1);' : '' }}"></div>
        <div class="shadow"></div>
    </div>
</div>