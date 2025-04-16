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

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($location) ? 'Update Location' : 'Create Location' }}
            </x-fonts.sub-header>
            @if (isset($location))
            <x-forms.delete-form :action="route('location.destroy')" label="Location" />
            @endif
        </x-slot>

        <form action="{{ route('location.update') }}" method="POST">
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
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary label="{{ isset($location) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>
<script>
    document.addLocationListener("DOMContentLoaded", function() {
        let dropdown = document.getElementById("type");
        if (dropdown.value !== '1') {
            document.getElementById('item_id_div').classList.add('hidden');
        }
    });

    function toggleTypeDropdown(location) {
        document.getElementById('item_id_div').classList.toggle('hidden', location.target.value !== '1');
    }
</script>