<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Egg Groups') }}
        </x-fonts.sub-header>
        <x-buttons.clear model="egg_group" route="egg_group.edit" icon="fa-plus" label="New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.alert type="Success" message="Saved!" />
    @endif
    
    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Egg Groups</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Egg groups are a way to categorize monsters based on their ability. Each egg group can be modified or created by the user, allowing for customization of how different species evolve. Each egg group contains a field that determines the specific monster type the eggs within the group will evolve into. This system makes it easier for trainers to organize their breeding programs and predict the potential evolutions of their monsters.
            </x-fonts.paragraph>
        </x-slot>

        <x-accordion title="Egg Groups" :open="true" icon="fa-egg">
            @if ($eggGroups->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="text-left w-1/3">Name</x-table.header>
                        <x-table.header class="text-left hidden md:table-cell w-1/3">Field</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eggGroups as $group)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="align-middle">
                            <div class="flex items-center gap-2">
                                <i class="fas {{ $group->icon }} text-xl text-accent"></i>
                                <x-fonts.paragraph class="font-bold text-text">{{ $group->name }}</x-fonts.paragraph>
                            </div>
                        </x-table.data>
                        <x-table.data class="hidden md:table-cell">
                            <x-fonts.paragraph class="text-text">{{ $group->field }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data>
                            <div class="flex justify-end items-center">
                                <x-buttons.session model="egg_group" :id="$group->id" route="egg_group.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No egg groups available.</x-fonts.paragraph>
            @endif
        </x-accordion>
    </x-container>
</x-app-layout>