<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($userTrainingEquipment) ? 'Update User Training Equipment' : 'Assign Training Equipment to User' }}
            </x-fonts.sub-header>
            <a href="{{ route('user.profile', ['id' => $user->id]) }}" class="mt-4 lg:mt-0">
                <x-primary-button>
                    Go Back <i class="fa fa-arrow-left ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-container>
        <form action="{{ route('user.equipment.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($userTrainingEquipment))
            <input type="hidden" name="id" value="{{ $userTrainingEquipment->id }}">
            @endif
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/4 mx-auto">

                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-inputs.label for="equipment_id">Training Equipment</x-inputs.input-label>
                                <x-inputs.dropdown id="equipment_id" name="equipment_id" class="form-control w-full" required>
                                    <option value="" {{ !isset($userTrainingEquipment) }}>Select Equipment</option>
                                    @foreach($allTrainingEquipments as $equipment)
                                    <option value="{{ $equipment->id }}"
                                        {{ isset($userTrainingEquipment) && $userTrainingEquipment->equipment_id == $equipment->id ? 'selected' : '' }}>
                                        {{ $equipment->name }} Max Level: {{ $equipment->max_level }}
                                    </option>
                                    @endforeach
                                </x-inputs.dropdown>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="level">Level</x-inputs.input-label>
                                <x-inputs.text type="number" name="level" id="level" class="form-control w-full" value="{{ isset($userTrainingEquipment) ? $userTrainingEquipment->level : 1 }}" min="1" required></x-inputs.text>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($trainingEquipment) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i></x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>