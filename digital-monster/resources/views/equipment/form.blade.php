<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($equipment) ? 'Update Equipment' : 'Create Equipment' }}
        </x-fonts.sub-header>
        <a href="{{ route('equipment.index') }}">
            <x-primary-button icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error/>
    @endif

    <x-container class="p-4">
        @if (isset($equipment))
        <x-forms.delete-form :action="route('equipment.destroy')" label="Training Equipment" />
        @endif
        <form action="{{ route('equipment.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.file label="Choose an Image" name="image" :currentImage="$equipment->image ?? null" />
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text
                                name="name"
                                divClasses="w-full"
                                value="{{ old('name', isset($equipment) ? $equipment->name : '') }}"
                                required />
                            <x-inputs.text
                                name="max_level"
                                type="number"
                                divClasses="w-full lg:w-1/4"
                                value="{{ old('max_level', isset($equipment) ? $equipment->max_level : '') }}"
                                required />
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.dropdown
                                name="stat"
                                divClasses="w-full lg:w-1/2"
                                :options="$stats"
                                :value="old('stat', isset($equipment) ? $equipment->stat : '')"
                                required />
                            <x-inputs.dropdown
                                name="upgrade_item_id"
                                divClasses="w-full lg:w-1/2"
                                :options="$materialItems->pluck('name', 'id')->toArray()"
                                useOptionKey="true"
                                :value="old('upgrade_item_id', isset($equipment) ? $equipment->upgrade_item_id : '')" />
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center py-4">
                    <x-primary-button type="submit" label="{{ isset($equipment) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>