<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($userDigitalMonster) ? 'Edit' : 'Create' }} Digital Monster
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('user.handleMonster', [$user->id, isset($userDigitalMonster) ? $userDigitalMonster->id : null]) }}" method="POST">
                    @csrf

                    <label for="digital_monster_id">Select Monster:</label>
                    <select name="digital_monster_id" id="digital_monster_id" class="form-control" required>
                        @foreach ($monstersByEgg as $monster)
                        <option value="{{ $monster->id }}" {{ isset($userDigitalMonster) && $userDigitalMonster->digital_monster_id == $monster->id ? 'selected' : '' }}>
                            Egg ID {{ $monster->egg_id }} Monster ID {{ $monster->monster_id }}
                        </option>
                        @endforeach
                    </select>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="Data" {{ (isset($userDigitalMonster) && $userDigitalMonster->type == 'Data') ? 'selected' : '' }}>Data</option>
                            <option value="Virus" {{ (isset($userDigitalMonster) && $userDigitalMonster->type == 'Virus') ? 'selected' : '' }}>Virus</option>
                            <option value="Vaccine" {{ (isset($userDigitalMonster) && $userDigitalMonster->type == 'Vaccine') ? 'selected' : '' }}>Vaccine</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Monster Name:</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="name" name="name" value="{{ isset($userDigitalMonster) ? $userDigitalMonster->name : '' }}" required>
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
            </div>
        </div>
    </div>
</x-app-layout>