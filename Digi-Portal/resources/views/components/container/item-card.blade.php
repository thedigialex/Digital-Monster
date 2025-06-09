@props([
'dataItem',
'buttonClass' => '',
'divClass' => '',
'isUserItem' => true,
'bottomText' => '',
])

@php
$item = $isUserItem ? $dataItem->item : $dataItem;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center w-36 bg-secondary rounded-md ' . $divClass]) }}>
    <x-fonts.paragraph class="bg-accent w-full rounded-t-md text-center border-b-2 border-primary">
        {{ $item->name }}
    </x-fonts.paragraph>

    <div class="w-24 h-24 p-4 rounded-md bg-primary m-2 relative">
        <button
            {{ $attributes->merge(['class' => 'w-full h-full ' . $buttonClass]) }}
            data-item='@json($item)'
            style="background: url('/storage/{{ $item->image }}') no-repeat; background-size: cover; background-position: 0 0;">
        </button>

        @if ($bottomText)
        <span class="absolute bottom-1 right-1 bg-accent text-text text-xs px-2 py-1 rounded-md">
            {{ $bottomText }}
        </span>
        @endif
    </div>
</div>