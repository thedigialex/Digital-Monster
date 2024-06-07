<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ isset($digitalMonster) ? 'Edit Monster' : 'Create Monster' }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        <form method="POST" action="{{ isset($digitalMonster) ? route('monsters.update', $digitalMonster->id) : route('monsters.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($digitalMonster))
            @method('PUT')
            @endif
            <div class="mb-4 flex space-x-4">
                <div class="w-1/3">
                    <x-input-label for="egg_id">Egg ID:</x-input-label>
                    <x-text-input id="egg_id" name="egg_id" value="{{ $digitalMonster->egg_id ?? '' }}" required></x-text-input>
                </div>
                <div class="w-1/3">
                    <x-input-label for="monster_id">Monster ID:</x-input-label>
                    <x-text-input id="monster_id" name="monster_id" value="{{ $digitalMonster->monster_id ?? '' }}" required></x-text-input>
                </div>
                <div class="w-1/3">
                    <x-input-label for="stage">Stage:</x-input-label>
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
                <x-input-label for="sprite_sheet">Sprite Sheet:</x-input-label>
                <input type="file" id="sprite_sheet" name="sprite_sheet" {{ isset($digitalMonster) ? '' : 'required' }}>
                @if(isset($digitalMonster) && $digitalMonster->sprite_sheet)
                <img src="{{ Storage::url($digitalMonster->sprite_sheet) }}" class="h-24 w-auto mt-2" alt="Current Sprite">
                @endif
            </div>
            <x-primary-button type="submit">
                {{ isset($digitalMonster) ? 'Update' : 'Create' }} Monster
            </x-primary-button>
        </form>
        @if(isset($digitalMonster))
        <div class="flex mt-4">
            <form method="POST" action="{{ route('monsters.destroy', $digitalMonster->id) }}" onsubmit="return confirm('Are you sure you want to delete this monster?');"  class="ml-auto">
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