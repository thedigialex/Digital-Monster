<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userDigitalMonster) ? 'Update Digital Monster' : 'Assign Digital Monster' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-primary-button icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error>
        Saving data, please fix fields.
    </x-alerts.error>
    @endif
    <x-container class="p-4">
        @if (isset($userDigitalMonster))
        <x-forms.delete-form :action="route('user.digital_monsters.destroy', $userDigitalMonster->id)" label="User Digital Mosnter" />
        @endif
        <form action="{{ route('user.digital_monsters.update') }}" method="POST" class="space-y-4">
            @csrf

            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/4 mx-auto">
                    <x-inputs.label for="digital_monster_id">Digital Monster</x-inputs.input-label>
                        <x-inputs.dropdown id="digital_monster_id" name="digital_monster_id" class="form-control w-full" required>
                            <option value="" {{ !isset($userDigitalMonster) }}>Select Digital Monster</option>
                            @foreach($allDigitalMonsters as $digitalMonster)
                            <option value="{{ $digitalMonster->id }}"
                                {{ isset($userDigitalMonster) && $userDigitalMonster->digital_monster_id == $digitalMonster->id ? 'selected' : '' }}>
                                ({{ $digitalMonster->eggGroup->name }}) {{ $digitalMonster->name }}
                            </option>
                            @endforeach
                        </x-inputs.dropdown>
                        <div class="flex space-x-4 mt-4">
                            <div class="w-full lg:w-1/2">
                                <x-inputs.label for="energy">Energy</x-inputs.input-label>
                                    <x-inputs.text type="number" name="energy" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->energy : 0 }}" required></x-inputs.text>

                            </div>
                            <div class="w-full lg:w-1/2">
                                <x-inputs.label for="maxEnergy">Max Energy</x-inputs.input-label>
                                    <x-inputs.text type="number" name="maxEnergy" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->maxEnergy : 0 }}" required></x-inputs.text>

                            </div>
                        </div>
                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-inputs.label for="name">Digital Monster Name</x-inputs.input-label>
                                <x-inputs.text type="text" name="name" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->name : '' }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="flex space-x-4">
                                <div class="w-full lg:w-1/2">
                                    <x-inputs.label for="type">Type</x-inputs.input-label>
                                        <x-inputs.dropdown name="type" class="form-control w-full" required>
                                            <option value="Data" {{ isset($userDigitalMonster) && $userDigitalMonster->type == 'Data' ? 'selected' : '' }}>Data</option>
                                            <option value="Virus" {{ isset($userDigitalMonster) && $userDigitalMonster->type == 'Virus' ? 'selected' : '' }}>Virus</option>
                                            <option value="Vaccine" {{ isset($userDigitalMonster) && $userDigitalMonster->type == 'Vaccine' ? 'selected' : '' }}>Vaccine</option>
                                        </x-inputs.dropdown>
                                </div>
                                <div class="w-full lg:w-1/2">
                                    <x-inputs.label for="isMain">Main</x-inputs.input-label>
                                        <x-inputs.dropdown name="isMain" id="isMain" class="form-control w-full" required>
                                            <option value="1" {{ isset($userDigitalMonster) && $userDigitalMonster->isMain == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ isset($userDigitalMonster) && $userDigitalMonster->isMain == 0 ? 'selected' : '' }}>No</option>
                                        </x-inputs.dropdown>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="w-full lg:w-1/3">
                            <x-inputs.label for="level">Level</x-inputs.input-label>
                                <x-inputs.text type="number" name="level" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->level : 1 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <x-inputs.label for="exp">Experience</x-inputs.input-label>
                                <x-inputs.text type="number" name="exp" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->exp : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <x-inputs.label for="currentEvoPoints">Current Evolution Points</x-inputs.input-label>
                                <x-inputs.text type="number" name="currentEvoPoints" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->currentEvoPoints : 0 }}" required></x-inputs.text>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="w-full lg:w-1/3">
                            <x-inputs.label for="hunger">Hunger</x-inputs.input-label>
                                <x-inputs.text type="number" name="hunger" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->hunger : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <x-inputs.label for="exercise">Exercise</x-inputs.input-label>
                                <x-inputs.text type="number" name="exercise" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->exercise : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/3">
                            <x-inputs.label for="clean">Cleanliness</x-inputs.input-label>
                                <x-inputs.text type="number" name="clean" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->clean : 0 }}" required></x-inputs.text>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="trainings">Trainings</x-inputs.input-label>
                                <x-inputs.text type="number" name="trainings" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->trainings : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="maxTrainings">Max Trainings</x-inputs.input-label>
                                <x-inputs.text type="number" name="maxTrainings" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->maxTrainings : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="wins">Wins</x-inputs.input-label>
                                <x-inputs.text type="number" name="wins" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->wins : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="losses">Losses</x-inputs.input-label>
                                <x-inputs.text type="number" name="losses" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->losses : 0 }}" required></x-inputs.text>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="strength">Strength</x-inputs.input-label>
                                <x-inputs.text type="number" name="strength" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->strength : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="agility">Agility</x-inputs.input-label>
                                <x-inputs.text type="number" name="agility" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->agility : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="defense">Defense</x-inputs.input-label>
                                <x-inputs.text type="number" name="defense" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->defense : 0 }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="mind">Mind</x-inputs.input-label>
                                <x-inputs.text type="number" name="mind" class="form-control w-full" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->mind : 0 }}" required></x-inputs.text>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($userDigitalMonster) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i></x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>