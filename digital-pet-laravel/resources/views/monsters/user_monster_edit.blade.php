<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ isset($userDigitalMonster) ? 'Edit' : 'Create' }} User Digital Monster
        </x-sub-header>
    </x-slot>


    <x-body-container>
        <form action="{{ route('user.handleMonster', [$user->id, isset($userDigitalMonster) ? $userDigitalMonster->id : null]) }}" method="POST">
            @csrf
            <x-input-label for="digital_monster_id">Select Monster:</x-input-label>
            <select name="digital_monster_id" id="digital_monster_id" class="form-control" required>
                @foreach ($monstersByEgg as $monster)
                <option value="{{ $monster->id }}" {{ isset($userDigitalMonster) && $userDigitalMonster->digital_monster_id == $monster->id ? 'selected' : '' }}>
                    Egg ID {{ $monster->egg_id }} Monster ID {{ $monster->monster_id }}
                </option>
                @endforeach
            </select>

            <div class="mb-4">
                <x-input-label for="type">Type:</x-input-label>
                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="Data" {{ (isset($userDigitalMonster) && $userDigitalMonster->type == 'Data') ? 'selected' : '' }}>Data</option>
                    <option value="Virus" {{ (isset($userDigitalMonster) && $userDigitalMonster->type == 'Virus') ? 'selected' : '' }}>Virus</option>
                    <option value="Vaccine" {{ (isset($userDigitalMonster) && $userDigitalMonster->type == 'Vaccine') ? 'selected' : '' }}>Vaccine</option>
                </select>
            </div>

            <div class="mb-4">
                <x-input-label for="name">Monster Name:</x-input-label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="name" name="name" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->name : '' }}" required>
            </div>

            <div class="mb-4">
                <x-input-label for="isMain">Is Main:</x-input-label>
                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="isMain" name="isMain" required>
                    <option value="0" {{ isset($userDigitalMonster) && !$userDigitalMonster->isMain ? 'selected' : '' }}>No</option>
                    <option value="1" {{ isset($userDigitalMonster) && $userDigitalMonster->isMain ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <x-primary-button type="submit">
                {{ isset($userDigitalMonster) ? 'Save Changes' : 'Create Monster' }}
            </x-primary-button>
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
    </x-body-container>
</x-app-layout>