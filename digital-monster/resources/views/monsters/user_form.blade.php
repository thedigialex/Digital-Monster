<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userMonster) ? 'Update Monster' : 'Assign Monster' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
    @endif

    <x-container class="p-4">
        @if (isset($userMonster))
        <x-forms.delete-form :action="route('user.monster.destroy')" label="User Mosnter" />
        @endif
        <form action="{{ route('user.monster.update') }}" method="POST" class="space-y-4">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.dropdown
                            name="monster_id"
                            divClasses="form-control w-full"
                            :options="$allMonsters->pluck('name', 'id')->toArray()"
                            useOptionKey="true"
                            :value="$userMonster->monster->id ?? ''" />
                        <div class="flex space-x-4 mt-4">
                            <x-inputs.text name="energy" type="number" divClasses="w-full lg:w-1/2" value="{{ isset($userMonster) ? $userMonster->energy : 0 }}" />
                            <x-inputs.text name="max_energy" type="number" divClasses="w-full lg:w-1/2" value="{{ isset($userMonster) ? $userMonster->max_energy : 0 }}" />
                        </div>
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text name="name" divClasses="w-full" value="{{ isset($userMonster) ? $userMonster->name : '' }}" />
                            <div class="w-full lg:w-1/2 flex space-x-4">
                                <x-inputs.dropdown
                                    name="type"
                                    divClasses="w-full lg:w-1/2"
                                    :options="['Data', 'Virus', 'Vaccine']"
                                    :value="$userMonster->type ?? ''" />
                                <x-inputs.dropdown
                                    name="main"
                                    divClasses="w-full lg:w-1/2"
                                    :options="['1' => 'Yes', '0' => 'No']"
                                    useOptionKey="true"
                                    :value="old('main', isset($userMonster) ? $userMonster->main : '')" />
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text name="level" type="number" divClasses="w-full lg:w-1/3" value="{{ $userMonster->level ?? 1 }}" required />
                            <x-inputs.text name="exp" type="number" divClasses="w-full lg:w-1/3" value="{{ $userMonster->exp ?? 0 }}" required />
                            <x-inputs.text name="evo_points" type="number" divClasses="w-full lg:w-1/3" value="{{ $userMonster->evo_points ?? 0 }}" required />
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text name="hunger" type="number" divClasses="w-full lg:w-1/3" value="{{ $userMonster->hunger ?? 0 }}" required />
                            <x-inputs.text name="exercise" type="number" divClasses="w-full lg:w-1/3" value="{{ $userMonster->exercise ?? 0 }}" required />
                            <x-inputs.text name="clean" type="number" divClasses="w-full lg:w-1/3" value="{{ $userMonster->clean ?? 0 }}" required />
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text name="trainings" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->trainings ?? 0 }}" required />
                            <x-inputs.text name="max_trainings" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->max_trainings ?? 0 }}" required />
                            <x-inputs.text name="wins" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->wins ?? 0 }}" required />
                            <x-inputs.text name="losses" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->losses ?? 0 }}" required />
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text name="strength" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->strength ?? 0 }}" required />
                            <x-inputs.text name="agility" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->agility ?? 0 }}" required />
                            <x-inputs.text name="defense" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->defense ?? 0 }}" required />
                            <x-inputs.text name="mind" type="number" divClasses="w-full lg:w-1/4" value="{{ $userMonster->mind ?? 0 }}" required />
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center py-4">
                    <x-buttons.primary type="submit" label="{{ isset($userMonster) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>