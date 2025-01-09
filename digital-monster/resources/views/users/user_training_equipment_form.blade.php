<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($trainingEquipment) ? 'Update Training Equipment' : 'Add New Training Equipment' }}
            </x-fonts.sub-header>
            <a href="{{ route('user.training_equipment', ['id' => $user->id]) }}">
                <x-primary-button>
                    Go Back
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-container>
        <form action="{{ route('user.training_equipment.update') }}" method="POST" class="space-y-4">
            @csrf
            @if (isset($trainingEquipment))
                <input type="hidden" name="id" value="{{ $trainingEquipment->id }}">
            @endif
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-input-label for="training_equipment_id">Training Equipment</x-input-label>
                    <x-text-dropdown id="training_equipment_id" name="training_equipment_id" class="form-control w-full" required>
                        <option value="" disabled {{ !isset($trainingEquipment) ? 'selected' : '' }}>Select Equipment</option>
                        @foreach($allTrainingEquipments as $equipment)
                            <option value="{{ $equipment->id }}"
                                {{ isset($trainingEquipment) && $trainingEquipment->training_equipment_id == $equipment->id ? 'selected' : '' }}>
                                {{ $equipment->name }}
                            </option>
                        @endforeach
                    </x-text-dropdown>
                </div>
                <div class="flex-1">
                    <x-input-label for="level">Level</x-input-label>
                    <x-text-input type="number" name="level" class="form-control w-full" value="{{ isset($trainingEquipment) ? $trainingEquipment->level : 1 }}" required></x-text-input>
                </div>
                <div class="flex-1">
                    <x-input-label for="stat_increase">Stat Increase</x-input-label>
                    <x-text-input type="number" name="stat_increase" class="form-control w-full" value="{{ isset($trainingEquipment) ? $trainingEquipment->stat_increase : 1 }}" required></x-text-input>
                </div>
            </div>

            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($trainingEquipment) ? 'Update' : 'Create' }}</x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>
