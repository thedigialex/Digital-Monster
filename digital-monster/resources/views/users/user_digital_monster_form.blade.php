<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($userDigitalMonster) ? 'Update Digital Monster' : 'Add New Digital Monster' }}
            </x-fonts.sub-header>
            <a href="{{ route('user.digital_monsters', ['id' => $user->id]) }}">
                <x-primary-button>
                    Go Back
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-container>
        <form action="{{ route('user.digital_monsters.update') }}" method="POST" class="space-y-4">
            @csrf
            @if (isset($userDigitalMonster))
            <input type="hidden" name="id" value="{{ $userDigitalMonster->id }}">
            @endif
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-inputs.label for="name">Digital Monster Name</x-inputs.input-label>
                    <x-inputs.text type="text" name="name" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->name : '' }}" required></x-inputs.text>
                </div>
                <div class="w-full md:w-1/3 px-2">
                    <x-inputs.label for="digital_monster_id">Digital Monsters</x-inputs.input-label>
                    <x-text-dropdown id="digital_monster_id" name="digital_monster_id" class="form-control w-full" required>
                        <option value="" disabled {{ !isset($userDigitalMonster) ? 'selected' : '' }}>Select Digital Monster</option>
                        @foreach($allDigitalMonsters as $digitalMonster)
                        <option value="{{ $digitalMonster->id }}"
                            {{ isset($userDigitalMonster) && $userDigitalMonster->digital_monster_id == $digitalMonster->id ? 'selected' : '' }}>
                            ({{ $digitalMonster->eggGroup->name }}) {{ $digitalMonster->name }}
                        </option>
                        @endforeach
                    </x-text-dropdown>
                </div>

                <div class="flex-1">
                    <x-inputs.label for="type">Type</x-inputs.input-label>
                    <x-text-dropdown name="type" class="form-control w-full" required>
                        <option value="Data" {{ isset($userDigitalMonster) && $userDigitalMonster->type == 'Data' ? 'selected' : '' }}>Data</option>
                        <option value="Virus" {{ isset($userDigitalMonster) && $userDigitalMonster->type == 'Virus' ? 'selected' : '' }}>Virus</option>
                        <option value="Vaccine" {{ isset($userDigitalMonster) && $userDigitalMonster->type == 'Vaccine' ? 'selected' : '' }}>Vaccine</option>
                    </x-text-dropdown>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-inputs.label for="level">Level</x-inputs.input-label>
                    <x-inputs.text type="number" name="level" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->level : 1 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="exp">Experience</x-inputs.input-label>
                    <x-inputs.text type="number" name="exp" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->exp : 0 }}" required></x-inputs.text>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-inputs.label for="strength">Strength</x-inputs.input-label>
                    <x-inputs.text type="number" name="strength" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->strength : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="agility">Agility</x-inputs.input-label>
                    <x-inputs.text type="number" name="agility" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->agility : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="defense">Defense</x-inputs.input-label>
                    <x-inputs.text type="number" name="defense" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->defense : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="mind">Mind</x-inputs.input-label>
                    <x-inputs.text type="number" name="mind" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->mind : 0 }}" required></x-inputs.text>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-inputs.label for="hunger">Hunger</x-inputs.input-label>
                    <x-inputs.text type="number" name="hunger" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->hunger : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="exercise">Exercise</x-inputs.input-label>
                    <x-inputs.text type="number" name="exercise" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->exercise : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="clean">Cleanliness</x-inputs.input-label>
                    <x-inputs.text type="number" name="clean" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->clean : 0 }}" required></x-inputs.text>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-inputs.label for="energy">Energy</x-inputs.input-label>
                    <x-inputs.text type="number" name="energy" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->energy : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="maxEnergy">Max Energy</x-inputs.input-label>
                    <x-inputs.text type="number" name="maxEnergy" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->maxEnergy : 0 }}" required></x-inputs.text>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-inputs.label for="wins">Wins</x-inputs.input-label>
                    <x-inputs.text type="number" name="wins" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->wins : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="losses">Losses</x-inputs.input-label>
                    <x-inputs.text type="number" name="losses" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->losses : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="trainings">Trainings</x-inputs.input-label>
                    <x-inputs.text type="number" name="trainings" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->trainings : 0 }}" required></x-inputs.text>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="maxTrainings">Max Trainings</x-inputs.input-label>
                    <x-inputs.text type="number" name="maxTrainings" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->maxTrainings : 0 }}" required></x-inputs.text>
                </div>
            </div>

            <div class="flex flex-col">
                <x-inputs.label for="currentEvoPoints">Current Evolution Points</x-inputs.input-label>
                <x-inputs.text type="number" name="currentEvoPoints" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->currentEvoPoints : 0 }}" required></x-inputs.text>
            </div>

            <div class="flex items-center">
                <x-inputs.label for="isMain">Is Main</x-inputs.input-label>
                <input type="checkbox" name="isMain" id="isMain"
                    {{ isset($userDigitalMonster) && $userDigitalMonster->isMain ? 'checked' : '' }}
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
            </div>

            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($userDigitalMonster) ? 'Update' : 'Create' }}</x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>