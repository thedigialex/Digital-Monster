@props([
'dataMonster',
'buttonClass' => '',
'divClass' => '',
])

@php
$monster = $dataMonster->monster ?? null;
$type = $dataMonster->type ?? null;
$stage = $monster->stage ?? null;

if (in_array($stage, ['Egg', 'Fresh', 'Child']) || $type === 'Data') {
$imgSrc = "/storage/" . $monster->image_0;
} elseif ($type === 'Vaccine') {
$imgSrc = "/storage/" . $monster->image_1;
} elseif ($type === 'Virus') {
$imgSrc = "/storage/" . $monster->image_2;
} else {
$imgSrc = '';
}
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center w-36 bg-secondary rounded-md ' . $divClass]) }}>
    <x-fonts.paragraph class="bg-accent w-full rounded-t-md text-center border-b-2 border-primary">
        {{ $dataMonster->name }}
    </x-fonts.paragraph>

    <div class="w-24 h-24 p-4 rounded-md bg-primary m-2">
        <button
            {{ $attributes->merge(['class' => 'w-full h-full ' . $buttonClass]) }}
            data-monster='@json($dataMonster)'
            style="background: url('{{ $imgSrc }}') no-repeat; background-size: cover; background-position: 0 0;">
        </button>
    </div>
</div>