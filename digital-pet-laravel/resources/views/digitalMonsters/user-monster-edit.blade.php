<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userDigitalMonster) ? 'Edit' : 'Create' }} User Digital Monster
        </x-fonts.sub-header>
    </x-slot>

    <x-elements.container>
        <form action="{{ route('user.handleMonster', [$user->id, isset($userDigitalMonster) ? $userDigitalMonster->id : null]) }}" method="POST">
            @csrf
            <x-input-label for="digital_monster_id">Select Monster:</x-input-label>
            <x-elements.select-input name="digital_monster_id" id="digital_monster_id" class="form-control" :options="$monsterOptions" :selected="$userDigitalMonster->digital_monster_id ?? null" required></x-elements.select-input>

            <div class="mb-4">
                <x-input-label for="type">Type:</x-input-label>
                <x-elements.select-input id="type" name="type" :options="['Data' => 'Data', 'Virus' => 'Virus', 'Vaccine' => 'Vaccine']" :selected="$userDigitalMonster->type ?? null" required></x-elements.select-input>
            </div>

            <div class="mb-4">
                <x-input-label for="name">Monster Name:</x-input-label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="name" name="name" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->name : '' }}" required>
            </div>

            <div class="mb-4">
                <x-input-label for="isMain">Is Main:</x-input-label>
                <x-elements.select-input id="isMain" name="isMain" :options="['0' => 'No', '1' => 'Yes']" :selected="$userDigitalMonster->isMain ?? null" required></x-elements.select-input>
            </div>

            <x-elements.primary-button type="submit">
                {{ isset($userDigitalMonster) ? 'Save Changes' : 'Create Monster' }}
            </x-elements.primary-button>
        </form>
        @if(isset($userDigitalMonster))
        <div class="flex mt-4">
            <form method="POST" action="{{ route('user.deleteMonster', [$user->id, $userDigitalMonster->id]) }}" onsubmit="return confirm('Are you sure you want to delete this monster?');" class="ml-auto">
                @csrf
                @method('DELETE')
                <x-delete-button type="submit">
                    Delete
                </x-delete-button>
            </form>
        </div>
        @endif
    </x-elements.container>
</x-app-layout>