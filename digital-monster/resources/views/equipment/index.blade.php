<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>Equipment</x-fonts.sub-header>
        <x-buttons.clear model="equipment" route="equipment.edit" icon="fa-plus" label="New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Equipment</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Egg groups are a way to categorize monsters based on their ability. Each egg group can be modified or created by the user, allowing for customization of how different species evolve. Each egg group contains a field that determines the specific monster type the eggs within the group will evolve into. This system makes it easier for trainers to organize their breeding programs and predict the potential evolutions of their monsters.
            </x-fonts.paragraph>
        </x-slot>
        @foreach ($stats as $index => $label)
        <x-accordion title="{{ $label }}" :open="$loop->first" :icon="$icons[$index]">
            @if (isset($allEquipment[$label]))
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 hidden md:table-cell text-left">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allEquipment[$label] as $equipment)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/4">
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $equipment->image) }}" alt="Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                        </x-table.data>
                        <x-table.data class="w-1/3 hidden md:table-cell">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $equipment->name }}
                            </x-fonts.paragraph>
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