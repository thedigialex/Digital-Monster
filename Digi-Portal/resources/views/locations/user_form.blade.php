<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userLocation) ? 'Update User Location' : 'Create User Location' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.alert type="Error" />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($userLocation) ? 'Update User Location' : 'Create User Location' }}
            </x-fonts.sub-header>
            @if (isset($userLocation))
            <x-forms.delete-form :action="route('user.location.destroy')" label="Location" />
            @endif
        </x-slot>

        <form action="{{ route('user.location.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.dropdown
                        name="location_id"
                        class="w-full md:w-1/2"
                        :options="$locations->pluck('name', 'id')->toArray()"
                        useOptionKey="true"
                        :value="old('location_id', isset($userLocation) ? $userLocation->location->id : '')"
                        :messages="$errors->get('location_id')" />
                    <x-inputs.text
                        name="steps"
                        class="w-full md:w-1/2"
                        :messages="$errors->get('steps')"
                        type="number"
                        :value="old('steps', isset($userLocation) ? $userLocation->steps : 1)" />
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.button type="edit" label="{{ isset($userLocation) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>