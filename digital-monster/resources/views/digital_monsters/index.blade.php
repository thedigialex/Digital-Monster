<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Digital Monsters') }}
        </x-fonts.sub-header>
        <x-buttons.clear-button model="digital_monster" route="digital_monsters.edit" icon="fa-plus" label="Add New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Digital Monsters</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Egg groups are a way to categorize monsters based on their ability. Each egg group can be modified or created by the user, allowing for customization of how different species evolve. Each egg group contains a field that determines the specific monster type the eggs within the group will evolve into. This system makes it easier for trainers to organize their breeding programs and predict the potential evolutions of their monsters.
            </x-fonts.paragraph>
        </x-slot>
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
                            <x-buttons.session-button model="digital_monster" :id="$digitalMonster->id" route="digital_monsters.edit" />
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