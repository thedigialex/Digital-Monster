<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                Shop
            </x-fonts.sub-header>
        </div>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header>
                    Shop
                </x-fonts.sub-header>
            </div>
        </x-slot>
        <div class="flex flex-col justify-center items-center bg-cover bg-center h-[60vh]"
            style="background-image: url('{{ asset($background) }}');">
            <div id="item-selection" class="flex flex-col w-1/2 overflow-y-auto">
                @foreach ($items as $type => $groupedItems)
                <h2 class="text-xl font-bold text-center text-white mb-2">{{ $type }}</h2>
                    <div class="flex flex-wrap justify-center items-center gap-4">
                        @foreach ($groupedItems as $item)
                        <div class="flex flex-col items-center w-28 p-2 bg-secondary border-2 border-accent rounded-md">
                            <div class="relative w-24 h-24 p-2 rounded-md bg-primary">
                                <button class="useItem w-full h-full"
                                    data-item='{{ json_encode($item) }}'
                                    style="background: url('/storage/{{ $item->image }}') no-repeat; background-size: cover; background-position: 0 0;">
                                </button>
                                <span class="absolute bottom-1 right-1 bg-accent text-text text-xs px-2 py-1 rounded-md">
                                    {{ $item->price }}
                                </span>
                            </div>
                            <x-fonts.paragraph> {{ $item->name }}</x-fonts.paragraph>
                        </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

    </x-container>
</x-app-layout>