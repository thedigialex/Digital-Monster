<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>Equipmentt</x-fonts.sub-header>
        <x-buttons.clear model="equipment" route="equipment.edit" icon="fa-plus" label="New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Equipment</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Equipment allows users to control their max level, image, and upgrade items. Each user has their own equipment, which they can upgrade, while the main equipment is managed solely by the admin.
            </x-fonts.paragraph>
        </x-slot>
        @foreach ($types as $index => $label)
        <x-accordion title="{{ $label }}" :open="$loop->first" :icon="$icons[$index]">
            @if (isset($allEquipment[$label]))
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 hidden md:table-cell text-left">Type</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allEquipment[$label] as $equipment)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/4">
                            <div class="w-16 h-16 flex items-center justify-center">
                                @if ($equipment->type == 'Stat')
                                <img src="{{ asset('storage/' . $equipment->image) }}" alt="Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                                @else
                                <i class="fa {{ $equipment->icon }} text-accent text-5xl"></i>
                                @endif
                            </div>
                        </x-table.data>
                        <x-table.data class="w-1/3 hidden md:table-cell">
                            @if ($equipment->type == 'Stat')
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $equipment->stat }}
                            </x-fonts.paragraph>
                            @else
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $equipment->type }}
                            </x-fonts.paragraph>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="equipment" :id="$equipment->id" route="equipment.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No equipment.</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>