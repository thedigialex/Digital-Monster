<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>Monsters</x-fonts.sub-header>
        <x-buttons.clear model="monster" route="monster.edit" icon="fa-plus" label="New" />
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
                This page serves as an index of digital monsters, neatly organized by their assigned Egg Groups. Each group brings together creatures with similar traits, making it easy to browse, compare, and discover new additions within a shared evolutionary theme.
            </x-fonts.paragraph>
        </x-slot>
        @foreach ($eggGroups as $index => $eggGroup)
        <x-accordion title="{{ ucfirst($eggGroup->name) }}" :open="$loop->first" :icon="$icons[$eggGroup->field]">
            @if ($eggGroup->monsters->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left hidden md:table-cell">Stage</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eggGroup->monsters as $monster)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            @if (isset($monster->image_0))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $monster->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/3 hidden md:table-cell">
                            <x-fonts.paragraph class="font-bold text-text">{{ $monster->stage }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="monster" :id="$monster->id" route="monster.edit" />
                            </div>
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