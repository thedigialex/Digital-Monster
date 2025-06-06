@props([
'dataItem',
'showQuantity' => true,
'buttonClass' => '',
'divClass' => '',
])



<div {{ $attributes->merge(['class' => 'flex flex-col items-center w-36 p-2 bg-secondary border-2 border-accent rounded-md ' . $divClass]) }}>
    <div class="relative w-24 h-24 p-2 rounded-md bg-primary">
        <button
            {{ $attributes->merge(['class' => 'w-full h-full ' . $buttonClass]) }}
            data-item='@json($dataItem)'
            style="background: url('/storage/{{ $dataItem->item->image }}') no-repeat; background-size: cover; background-position: 0 0;">
        </button>

        @if ($showQuantity)
        <span class="absolute bottom-1 right-1 bg-accent text-text text-xs px-2 py-1 rounded-md">
            {{ $dataItem->quantity }}
        </span>
        @endif
    </div>

    <x-fonts.paragraph>{{ $dataItem->item->name }}</x-fonts.paragraph>
</div>