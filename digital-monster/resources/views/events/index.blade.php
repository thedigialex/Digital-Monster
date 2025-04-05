<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Events
        </x-fonts.sub-header>
        <x-buttons.clear model="event" route="event.edit" icon="fa-plus" label="New" />
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Events</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Egg groups are a way to categorize monsters based on their ability. Each egg group can be modified or created by the user, allowing for customization of how different species evolve. Each egg group contains a field that determines the specific monster type the eggs within the group will evolve into. This system makes it easier for trainers to organize their breeding programs and predict the potential evolutions of their monsters.
            </x-fonts.paragraph>
        </x-slot>
        <x-accordion title="Event" :open="true" :icon="'fa-calendar'">
            @if ($events->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-2/3 text-left">Message</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-2/3">
                            <x-fonts.paragraph class="font-bold text-text"> {{ Str::limit($event->message, 50) }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="event" :id="$event->id" route="event.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No events available</x-fonts.paragraph>
            @endif
        </x-accordion>
    </x-container>
</x-app-layout>