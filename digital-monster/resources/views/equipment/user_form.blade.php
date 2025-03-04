<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userEquipment) ? 'Update User Equipment' : 'Create User Equipment' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error/>
    @endif

    <x-container class="p-4">
        @if (isset($userEquipment))
        <x-forms.delete-form :action="route('user.equipment.destroy')" label="Equipment" />
        @endif
        <form action="{{ route('user.equipment.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4 w-full">
                    <x-inputs.dropdown name="equipment_id" divClasses="w-full" required :options="$allEquipment->pluck('name', 'id')->toArray()" useOptionKey="true" :value="old('equipment_id', isset($userEquipment) ? $userEquipment->equipment->id : '')" />
                    <x-inputs.text
                        name="level"
                        divClasses="w-full"
                        required
                        type="number"
                        :value="old('level', isset($userEquipment) ? $userEquipment->level : 1)" />
                </div>
                <div class="flex justify-center py-4">
                    <x-buttons.primary type="submit" label="{{ isset($userEquipment) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>