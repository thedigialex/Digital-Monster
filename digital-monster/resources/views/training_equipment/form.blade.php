<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($trainingEquipment) ? 'Update Training Equipment' : 'Create Training Equipment' }}
        </x-fonts.sub-header>
        <a href="{{ route('trainingEquipments.index') }}">
            <x-primary-button icon="fa-arrow-left">
                Go Back
            </x-primary-button>
        </a>
    </x-slot>

    @if ($errors->any())
    <x-slot name="alert">
        <x-alerts.error>
            Saving data, please fix fields.
        </x-alerts.error>
    </x-slot>
    @endif

    <x-container class="p-4">
        <div class="flex justify-end w-full">
            @if (isset($trainingEquipment))
            <form action="{{ route('trainingEquipments.destroy', $trainingEquipment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this training equipment?');">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit" icon="fa-trash">
                    Delete
                </x-danger-button>
            </form>
            @endif
        </div>

        <form action="{{ route('trainingEquipments.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($trainingEquipment))
            <input type="hidden" name="id" value="{{ $trainingEquipment->id }}">
            @endif

            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.file label="Choose an Image" name="image" :currentImage="$trainingEquipment->image ?? null" />
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text
                                name="name"
                                divClasses="w-full"
                                value="{{ isset($trainingEquipment) ? $trainingEquipment->name : '' }}"
                                required />
                            <x-inputs.text
                                name="max_level"
                                type="number"
                                divClasses="w-full lg:w-1/4"
                                value="{{ isset($trainingEquipment) ? $trainingEquipment->max_level : '' }}"
                                required />
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.dropdown
                                name="stat"
                                divClasses="w-full lg:w-1/2"
                                :options="$stats"
                                :value="isset($trainingEquipment) ? $trainingEquipment->stat : ''"
                                required />
                            <x-inputs.dropdown
                                name="upgrade_item_id"
                                divClasses="w-full lg:w-1/2"
                                :options="$materialItems->pluck('name', 'id')->toArray()"
                                useOptionKey="true"
                                :value="isset($trainingEquipment) ? $trainingEquipment->upgrade_item_id : ''" />
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center">
                    <x-primary-button type="submit" icon="fa-save">
                        {{ isset($trainingEquipment) ? 'Update' : 'Create' }}
                    </x-primary-button>
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>