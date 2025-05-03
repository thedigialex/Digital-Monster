<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Egg Groups') }}
        </x-fonts.sub-header>
        <x-buttons.clear model="egg_group" route="egg_group.edit" icon="fa-plus" label="New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.alert type="Success" message="Saved!"  />
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Egg Groups</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Egg groups are a way to categorize monsters based on their ability. Each egg group can be modified or created by the user, allowing for customization of how different species evolve. Each egg group contains a field that determines the specific monster type the eggs within the group will evolve into. This system makes it easier for trainers to organize their breeding programs and predict the potential evolutions of their monsters.
            </x-fonts.paragraph>
        </x-slot>
        @foreach ($fields as $index => $label)
        <x-accordion title="{{ $label }}" :open="$loop->first" :icon="$icons[$index]">
            @if (isset($eggGroups[$label]) && $eggGroups[$label]->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-2/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eggGroups[$label] as $eggGroup)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-2/3">
                            <x-fonts.paragraph class="font-bold text-text">{{ $eggGroup->name }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="egg_group" :id="$eggGroup->id" route="egg_group.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No egg groups available for this field</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>