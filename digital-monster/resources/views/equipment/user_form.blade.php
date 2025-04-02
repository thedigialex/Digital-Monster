<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userEquipment) ? 'Update User Equipment' : 'Create User Equipment' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($userEquipment) ? 'Update User Equipment' : 'Create User Equipment' }}
            </x-fonts.sub-header>
            @if (isset($userEquipment))
            <x-forms.delete-form :action="route('user.equipment.destroy')" label="Equipment" />
            @endif
        </x-slot>

        <form action="{{ route('user.equipment.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.dropdown name="equipment_id" class="w-full" :messages="$errors->get('equipment_id')" :options="$allEquipment->mapWithKeys(fn($item) => [$item->id => $item->type . ' ' . $item->stat])->toArray()"
                        useOptionKey="true" :value="old('equipment_id', isset($userEquipment) ? $userEquipment->equipment->id : '')" />
                    <x-inputs.text
                        name="level"
                        class="w-full"
                        :messages="$errors->get('level')"
                        type="number"
                        :value="old('level', isset($userEquipment) ? $userEquipment->level : 1)" />
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary label="{{ isset($userEquipment) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>