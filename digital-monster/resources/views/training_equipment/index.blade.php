<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Training Equipment') }}
        </x-fonts.sub-header>
        <a href="{{ route('trainingEquipments.edit') }}">
            <x-primary-button icon="fa-plus">
                Add New
            </x-primary-button>
        </a>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        @foreach ($stats as $index => $label)
        <x-accordion title="{{ $label }}" :open="$loop->first" :icon="$icons[$index]">
            @if (isset($trainingEquipments[$label]) && $trainingEquipments[$label]->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainingEquipments[$label] as $equipment)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/3">
                            @if (isset($equipment->image))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $equipment->image) }}" alt="Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $equipment->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3 text-end">
                            <a href="{{ route('trainingEquipments.edit', ['id' => $equipment->id]) }}">
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
            <x-fonts.paragraph class="text-text p-4">No equipment.</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>