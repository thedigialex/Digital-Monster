<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Items') }}
        </x-fonts.sub-header>
        <a href="{{ route('items.edit') }}">
            <x-primary-button icon="fa-plus">
                Add New
            </x-primary-button>
        </a>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        @foreach ($itemTypes as $type)
        <x-accordion title="{{ ucfirst($type) }}" :open="$loop->first">
            @if (isset($items[$type]) && $items[$type]->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items[$type] as $item)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/3">
                            @if (isset($item->image))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <x-fonts.paragraph class="font-bold {{ $item->isAvailable == 1 ? 'text-accent' : 'text-error' }}">
                                {{ $item->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3 text-end">
                            <a href="{{ route('items.edit', ['id' => $item->id]) }}">
                                <x-primary-button icon="fa-edit">
                                    Edit
                                </x-primary-button>
                            </a>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No items.</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>