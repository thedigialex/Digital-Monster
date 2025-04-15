<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Items') }}
        </x-fonts.sub-header>
        <x-buttons.clear model="item" route="item.edit" icon="fa-plus" label="New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Items</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                This page provides an index of all in-game items, including backgrounds, attacks, consumables, and materials. Designed for administrative use, it allows for easy management, editing, and organization of item data across the system.
            </x-fonts.paragraph>
        </x-slot>
        @foreach ($itemTypes as $type)
        <x-accordion title="{{ ucfirst($type) }}" :open="$loop->first" :icon="$icons[$type]">
            @if (isset($items[$type]) && $items[$type]->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left hidden md:table-cell">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items[$type] as $item)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            @if (isset($item->image))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/3 w-1/4 hidden md:table-cell">
                            <x-fonts.paragraph class="font-bold {{ $item->available == 1 ? 'text-accent' : 'text-error' }}">
                                {{ $item->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="item" :id="$item->id" route="item.edit" />
                            </div>
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