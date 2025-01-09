<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($trainingEquipment) ? 'Update Training Equipment' : 'Create Training Equipment' }}
            </x-fonts.sub-header>
            <a href="{{ route('trainingEquipments.index') }}">
                <x-primary-button>
                    Go Back
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
            <div>
                <x-input-label for="name">Name</x-input-label>
                <x-text-input type="text" name="name" class="form-control w-full" value="{{ isset($trainingEquipment) ? $trainingEquipment->name : '' }}" required></x-text-input>
            </div>
            <div>
                <x-input-label for="stat">Stat</x-input-label>
                <select name="stat" id="stat" class="form-control w-full" required>
                    <option value="" disabled {{ isset($trainingEquipment) && !$trainingEquipment->stat ? 'selected' : '' }}>Select a stat</option>
                    @foreach ($stats as $value => $label)
                        <option value="{{ $value }}" {{ isset($trainingEquipment) && $trainingEquipment->stat === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Image Upload Section -->
            <div>
                <x-input-label for="image">Image</x-input-label>
                <input type="file" name="image" id="image" class="form-control w-full" accept="image/*">
                @if (isset($trainingEquipment) && $trainingEquipment->image)
                    <img src="{{ asset('storage/' . $trainingEquipment->image) }}" alt="Training Equipment Image" class="mt-2 w-32 h-32 object-cover">
                @endif
            </div>

            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($trainingEquipment) ? 'Update' : 'Create' }}</x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>
