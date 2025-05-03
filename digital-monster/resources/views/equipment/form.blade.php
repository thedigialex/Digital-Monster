<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($equipment) ? 'Update Equipment' : 'Create Equipment' }}
        </x-fonts.sub-header>
        <a href="{{ route('equipment.index') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.alert type="Error" />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($equipment) ? 'Update Equipment' : 'Create Equipment' }}
            </x-fonts.sub-header>
            @if (isset($equipment))
            <x-forms.delete-form :action="route('equipment.destroy')" label="Training Equipment" />
            @endif
        </x-slot>

        <form action="{{ route('equipment.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.dropdown
                        name="icon"
                        class="w-full lg:w-1/2"
                        :options="$icon"
                        :value="old('icon', isset($equipment) ? $equipment->icon : '')"
                        :messages="$errors->get('icon')" />
                    <x-inputs.dropdown
                        name="type"
                        class="w-full lg:w-1/2"
                        :options="$type"
                        :value="old('type', isset($equipment) ? $equipment->type : '')"
                        :messages="$errors->get('type')"
                        onchange="toggleStatDropdown(event)" />
                    <x-inputs.dropdown
                        name="stat"
                        class="w-full lg:w-1/3 hidden"
                        :options="$stats"
                        :value="old('stat', isset($equipment) ? $equipment->stat : '')"
                        :messages="$errors->get('stat')" />
                </div>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.text
                        name="max_level"
                        type="number"
                        class="w-full lg:w-1/2"
                        value="{{ old('max_level', isset($equipment) ? $equipment->max_level : '') }}"
                        :messages="$errors->get('max_level')" />
                    <x-inputs.dropdown
                        name="upgrade_item_id"
                        class="w-full lg:w-1/2"
                        :options="$materialItems->pluck('name', 'id')->toArray()"
                        useOptionKey="true"
                        :value="old('upgrade_item_id', isset($equipment) ? $equipment->upgrade_item_id : '')" />
                </div>
                <div class="flex flex-col justify-center items-center py-4 space-y-4">
                    <x-inputs.file label="Choose an Image" name="image" class="hidden w-full md:w-1/3" :currentImage="$equipment->image ?? null" :messages="$errors->get('image')" />
                    <x-buttons.primary label="{{ isset($equipment) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let dropdown = document.getElementById("type");
        document.getElementById('stat_div').classList.toggle('hidden', dropdown.value !== 'Stat');
        document.getElementById('image_div').classList.toggle('hidden', dropdown.value !== 'Stat');
    });

    function toggleStatDropdown(event) {
        document.getElementById('stat_div').classList.toggle('hidden', event.target.value !== 'Stat');
        document.getElementById('image_div').classList.toggle('hidden', event.target.value !== 'Stat');
    }
</script>