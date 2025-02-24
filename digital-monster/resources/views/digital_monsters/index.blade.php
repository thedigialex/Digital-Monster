<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Digital Monsters') }}
        </x-fonts.sub-header>
        <a href="{{ route('digital_monsters.edit') }}">
            <x-primary-button icon="fa-plus">
                Add New
            </x-primary-button>
        </a>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        @foreach ($eggGroups as $index => $eggGroup)
        <x-accordion title="{{ ucfirst($eggGroup->name) }}" :open="$loop->first" :icon="$icons[$eggGroup->field_type]">
            @if ($eggGroup->digitalMonsters->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/4 text-left">Image</x-table.header>
                        <x-table.header class="w-1/4 text-left">Name</x-table.header>
                        <x-table.header class="w-1/4 text-left">Stage</x-table.header>
                        <x-table.header class="w-1/4"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eggGroup->digitalMonsters as $digitalMonster)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/4">
                            @if (isset($digitalMonster->sprite_image_0))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $digitalMonster->sprite_image_0) }}" alt="Digital Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/4">
                            <x-fonts.paragraph class="font-bold text-text">{{ $digitalMonster->name }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/4">
                            <x-fonts.paragraph class="font-bold text-text">{{ $digitalMonster->stage }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/4 text-end space-x-4">
                            <a href="{{ route('digital_monsters.edit', ['id' => $digitalMonster->id]) }}">
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
            <x-fonts.paragraph class="text-text p-4">No digital monsters.</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>