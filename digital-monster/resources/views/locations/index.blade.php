<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Locations
        </x-fonts.sub-header>
        <x-buttons.clear model="location" route="location.edit" icon="fa-plus" label="New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Locations</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                This page lists all the possible events that can occur during Adventure Mode. Each entry gives a glimpse into the encounters, surprises, and challenges that may unfold as you explore, offering a handy guide to what might lie ahead.
            </x-fonts.paragraph>
        </x-slot>
        <x-accordion title="Locations" :open="true" :icon="'fa-location-crosshairs'">
            @if ($locations->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-2/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-2/3">
                            <x-fonts.paragraph class="font-bold text-text"> {{ $location->name }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="location" :id="$location->id" route="location.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No locations available</x-fonts.paragraph>
            @endif
        </x-accordion>
    </x-container>
</x-app-layout>