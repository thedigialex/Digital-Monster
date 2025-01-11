<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($trainingEquipment) ? 'Update Training Equipment' : 'Create Training Equipment' }}
            </x-fonts.sub-header>
            <a href="{{ route('trainingEquipments.index') }}" class="mt-4 lg:mt-0">
                <x-primary-button>
                    Go Back <i class="fa fa-arrow-left ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-container>
        <form action="{{ route('trainingEquipments.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($trainingEquipment))
            <input type="hidden" name="id" value="{{ $trainingEquipment->id }}">
            @endif
            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/4 mx-auto">
                    <x-inputs.label for="image">Image</x-inputs.input-label>
                        @if (isset($trainingEquipment) && $trainingEquipment->image)
                        <div class="mt-2 w-32 h-32 overflow-hidden mx-auto">
                            <img src="{{ asset('storage/' . $trainingEquipment->image) }}" alt="Training Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                        </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control w-full mt-2" accept="image/*">
                </div>

                <div class="flex-1 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-inputs.label for="name">Name</x-inputs.input-label>
                                <x-inputs.text type="text" name="name" id="name" class="form-control w-full" value="{{ isset($trainingEquipment) ? $trainingEquipment->name : '' }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="max_level">Max Level</x-inputs.input-label>
                                <x-inputs.text type="number" name="max_level" id="max_level" class="form-control w-full" value="{{ isset($trainingEquipment) ? $trainingEquipment->max_level : '' }}" min="1" max="5" required></x-inputs.text>
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="w-full lg:w-1/2">
                            <x-inputs.label for="stat">Stat</x-inputs.input-label>
                                <x-inputs.dropdown name="stat" id="stat" class="form-control w-full" required>
                                    <option value="" disabled {{ isset($trainingEquipment) && !$trainingEquipment->stat ? 'selected' : '' }}>Select a stat</option>
                                    @foreach ($stats as $index => $label)
                                    <option value="{{ $label }}" {{ isset($trainingEquipment) && $trainingEquipment->stat === $label ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </x-inputs.dropdown>
                        </div>
                        <div class="w-full lg:w-1/2">
                            <x-inputs.label for="upgrade_item_id">Upgrade Item</x-inputs.input-label>
                                <x-inputs.dropdown name="upgrade_item_id" id="upgrade_item_id" class="form-control w-full">
                                    <option value="" {{ isset($trainingEquipment) && !$trainingEquipment->upgrade_item_id ? 'selected' : '' }}>
                                        Select an Upgrade Item
                                    </option>
                                    @foreach ($materialItems as $item)
                                    <option value="{{ $item->id }}"
                                        {{ isset($trainingEquipment) && $trainingEquipment->upgrade_item_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </x-inputs.dropdown>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($trainingEquipment) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i> </x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>