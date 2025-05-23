<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userMonster) ? 'Update Monster' : 'Assign Monster' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.alert type="Error" />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($userMonster) ? 'Update Monster' : 'Assign Monster' }}
            </x-fonts.sub-header>
            @if (isset($userMonster))
            <x-forms.delete-form :action="route('user.monster.destroy')" label="User Mosnter" />
            @endif
        </x-slot>

        <form action="{{ route('user.monster.update') }}" method="POST">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.dropdown
                            name="monster_id"
                            class="form-control w-full"
                            :options="$allMonsters->pluck('name', 'id')->toArray()"
                            useOptionKey="true"
                            :messages="$errors->get('monster_id')"
                            :value="$userMonster->monster->id ?? ''" />
                        <div class="flex space-x-4">
                            <x-inputs.text name="energy" type="number" class="w-full lg:w-1/2" value="{{ isset($userMonster) ? $userMonster->energy : 0 }}" :messages="$errors->get('energy')" />
                            <x-inputs.text name="max_energy" type="number" class="w-full lg:w-1/2" value="{{ isset($userMonster) ? $userMonster->max_energy : 0 }}" :messages="$errors->get('max_energy')" />
                        </div>
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col md:flex-row gap-x-4">
                            <x-inputs.text name="name" class="w-full" value="{{ isset($userMonster) ? $userMonster->name : '' }}" :messages="$errors->get('name')" />
                            <div class="w-full lg:w-1/2 flex space-x-4">
                                <x-inputs.dropdown
                                    name="type"
                                    :messages="$errors->get('type')"
                                    class="w-full lg:w-1/2"
                                    :options="['Data', 'Vaccine', 'Virus']"
                                    :value="$userMonster->type ?? ''" />
                                <x-inputs.dropdown
                                    name="main"
                                    :messages="$errors->get('main')"
                                    class="w-full lg:w-1/2"
                                    :options="['1' => 'Yes', '0' => 'No']"
                                    useOptionKey="true"
                                    :value="old('main', isset($userMonster) ? $userMonster->main : '')" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4">
                            <x-inputs.text name="level" type="number" class="w-full lg:w-1/3" value="{{ $userMonster->level ?? 1 }}" :messages="$errors->get('level')" />
                            <x-inputs.text name="exp" type="number" class="w-full lg:w-1/3" value="{{ $userMonster->exp ?? 0 }}" :messages="$errors->get('exp')" />
                            <x-inputs.text name="evo_points" type="number" class="w-full lg:w-1/3" value="{{ $userMonster->evo_points ?? 0 }}" :messages="$errors->get('evo_points')" />
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4">
                            <x-inputs.text name="hunger" type="number" class="w-full lg:w-1/3" value="{{ $userMonster->hunger ?? 0 }}" :messages="$errors->get('hunger')" />
                            <x-inputs.text name="exercise" type="number" class="w-full lg:w-1/3" value="{{ $userMonster->exercise ?? 0 }}" :messages="$errors->get('exercise')" />
                            <x-inputs.text name="clean" type="number" class="w-full lg:w-1/3" value="{{ $userMonster->clean ?? 0 }}" :messages="$errors->get('clean')" />
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4">
                            <x-inputs.text name="trainings" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->trainings ?? 0 }}" :messages="$errors->get('trainings')" />
                            <x-inputs.text name="max_trainings" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->max_trainings ?? 0 }}" :messages="$errors->get('max_trainings')" />
                            <x-inputs.text name="wins" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->wins ?? 0 }}" :messages="$errors->get('wins')" />
                            <x-inputs.text name="losses" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->losses ?? 0 }}" :messages="$errors->get('losses')" />
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4">
                            <x-inputs.text name="strength" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->strength ?? 0 }}" :messages="$errors->get('strength')" />
                            <x-inputs.text name="agility" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->agility ?? 0 }}" :messages="$errors->get('agility')" />
                            <x-inputs.text name="defense" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->defense ?? 0 }}" :messages="$errors->get('defense')" />
                            <x-inputs.text name="mind" type="number" class="w-full lg:w-1/4" value="{{ $userMonster->mind ?? 0 }}" :messages="$errors->get('mind')" />
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.button type="edit" label="{{ isset($userMonster) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>