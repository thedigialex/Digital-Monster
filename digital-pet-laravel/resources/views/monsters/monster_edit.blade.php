<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-xl font-bold mb-4">{{ isset($digitalMonster) ? 'Edit Monster' : 'Create Monster' }}</h1>

                <form method="POST" action="{{ isset($digitalMonster) ? route('monsters.update', $digitalMonster->id) : route('monsters.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($digitalMonster))
                    @method('PUT')
                    @endif

                    <div class="mb-4 flex space-x-4">
                        <div class="w-1/3">
                            <label for="monster_id" class="block text-sm font-medium text-gray-700">Monster ID:</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="monster_id" name="monster_id" value="{{ $digitalMonster->monster_id ?? '' }}" required>
                        </div>

                        <div class="w-1/3">
                            <label for="egg_id" class="block text-sm font-medium text-gray-700">Egg ID:</label>
                            <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="egg_id" name="egg_id" value="{{ $digitalMonster->egg_id ?? '' }}" required>
                        </div>

                        <div class="w-1/3">
                            <label for="stage" class="block text-sm font-medium text-gray-700">Stage:</label>
                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="stage" name="stage" required>
                                <option value="">Select Stage</option>
                                <option value="Egg" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Egg') ? 'selected' : '' }}>Egg</option>
                                <option value="Fresh" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Fresh') ? 'selected' : '' }}>Fresh</option>
                                <option value="Child" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Child') ? 'selected' : '' }}>Child</option>
                                <option value="Rookie" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Rookie') ? 'selected' : '' }}>Rookie</option>
                                <option value="Champion" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Champion') ? 'selected' : '' }}>Champion</option>
                                <option value="Ultimate" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Ultimate') ? 'selected' : '' }}>Ultimate</option>
                                <option value="Final" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Final') ? 'selected' : '' }}>Final</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="sprite_sheet" class="block text-sm font-medium text-gray-700">Sprite Sheet:</label>
                        <input type="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="sprite_sheet" name="sprite_sheet" {{ isset($digitalMonster) ? '' : 'required' }}>
                        @if(isset($digitalMonster) && $digitalMonster->sprite_sheet)
                        <img src="{{ Storage::url($digitalMonster->sprite_sheet) }}" class="h-24 w-auto mt-2" alt="Current Sprite">
                        @endif
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md">
                        {{ isset($digitalMonster) ? 'Update' : 'Create' }} Monster
                    </button>
                </form>
                @if(isset($digitalMonster))
                <form method="POST" action="{{ route('monsters.destroy', $digitalMonster->id) }}" onsubmit="return confirm('Are you sure you want to delete this monster?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md">Delete</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>