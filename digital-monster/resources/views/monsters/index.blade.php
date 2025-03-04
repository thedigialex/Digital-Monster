<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>Monsters</x-fonts.sub-header>
        <x-buttons.clear model="monster" route="monster.edit" icon="fa-plus" label="Add New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Monsters</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Egg groups are a way to categorize monsters based on their ability. Each egg group can be modified or created by the user, allowing for customization of how different species evolve. Each egg group contains a field that determines the specific monster type the eggs within the group will evolve into. This system makes it easier for trainers to organize their breeding programs and predict the potential evolutions of their monsters.
            </x-fonts.paragraph>
        </x-slot>
        @foreach ($eggGroups as $index => $eggGroup)
        <x-accordion title="{{ ucfirst($eggGroup->name) }}" :open="$loop->first" :icon="$icons[$eggGroup->field]">
            @if ($eggGroup->monsters->isNotEmpty())
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
                    @foreach ($eggGroup->monsters as $monster)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/4">
                            @if (isset($monster->image_0))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $monster->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/4">
                            <x-fonts.paragraph class="font-bold text-text">{{ $monster->name }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/4">
                            <x-fonts.paragraph class="font-bold text-text">{{ $monster->stage }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/4 text-end space-x-4">
                            <x-buttons.session model="monster" :id="$monster->id" route="monster.edit" />
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No monsters</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>