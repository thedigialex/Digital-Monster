<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($location) ? 'Update Location' : 'Create Location' }}
        </x-fonts.sub-header>
        <a href="{{ route('locations.index') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
    @endif
    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($location) ? 'Update Location' : 'Create Location' }}
            </x-fonts.sub-header>
            @if (isset($location))
            <x-forms.delete-form :action="route('location.destroy')" label="Location" />
            @endif
        </x-slot>

        <form action="{{ route('location.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4 w-full">
                    <x-inputs.text
                        name="name"
                        class="w-full"
                        value="{{ old('name', isset($location) ? $location->name : '') }}"
                        :messages="$errors->get('name')" />
                    <div class="flex space-x-4">
                        <x-inputs.text name="unlock_steps" type="number" class="w-full lg:w-1/2" value="{{ isset($location) ? $location->unlock_steps : 0 }}" :messages="$errors->get('unlock_steps')" />
                        <x-inputs.dropdown
                            name="unlock_location_id"
                            class="w-full md:w-1/2"
                            :options="$otherLocations->pluck('name', 'id')->toArray()"
                            useOptionKey="true"
                            :value="old('unlock_location_id', isset($location) ? $location->unlock_location_id : '')"
                            :messages="$errors->get('unlock_location_id')" />
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-x-4 w-full">
                    <x-inputs.text
                        name="description"
                        class="w-full"
                        value="{{ old('description', isset($location) ? $location->description : '') }}"
                        :messages="$errors->get('description')" />
                </div>
                <div class="flex flex-col justify-center items-center py-4 space-y-4">
                    <x-inputs.file label="Choose an Image" name="image" class="w-1/3" :currentImage="$location->image ?? null" :messages="$errors->get('image')" />
                    <x-buttons.primary label="{{ isset($location) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>

        @if (isset($location))
        <x-accordion title="Events" :open="false" :icon="'fa-calendar'">
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
                            <x-fonts.paragraph class="font-bold text-text">{{ Str::limit($event->message, 50) }}
                            </x-fonts.paragraph>
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
            <div class="flex justify-center py-4 mt-4">
                <x-buttons.clear model="event" route="event.edit" icon="fa-plus" label="New" />
            </div>
        </x-accordion>
        @endif
    </x-container>
</x-app-layout>