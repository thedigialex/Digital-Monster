@props(['id', 'rotate' => false])

<div class="w-16 h-16 p-2">
    <div id="{{ $id }}" class="w-full h-full" style="{{ $rotate ? 'transform: scaleX(-1);' : '' }}"></div>
    <div class="shadow"></div>
</div>