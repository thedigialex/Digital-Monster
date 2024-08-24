<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($digitalMonster) ? 'Edit Monster' : 'Create Monster' }}
        </x-fonts.sub-header>
    </x-slot>

    <x-elements.container>
        <form method="POST" action="{{ route('digitalMonsters.handle', $digitalMonster->id ?? null) }}" enctype="multipart/form-data">
            @csrf
            @if(isset($digitalMonster))
            @method('PUT')
            @endif

            <div class="mb-4 flex space-x-4">
                <div class="w-1/3">
                    <x-input-label for="egg_id">Egg ID:</x-input-label>
                    <select id="egg_id" name="egg_id" required>
                        @foreach ($eggGroups as $id => $name)
                        <option value="{{ $id }}" {{ (isset($digitalMonster->eggId) && $digitalMonster->eggId == $id) ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-1/3">
                    <x-input-label for="monster_id">Monster ID:</x-input-label>
                    <x-elements.select-input id="monster_id" name="monster_id" :options="$options" required :selected="$digitalMonster->monsterId ?? ''"></x-elements.select-input>
                </div>
            </div>
            <div class="mb-4">
                <x-input-label for="sprite_sheet">Sprite Sheet:</x-input-label>
                <input type="file" id="sprite_sheet" name="sprite_sheet" {{ isset($digitalMonster) ? '' : 'required' }}>
                @if(isset($digitalMonster) && $digitalMonster->spriteSheet)
                <img src="{{ Storage::url($digitalMonster->spriteSheet) }}" class="h-24 w-auto mt-2" alt="Current Sprite">
                @endif
            </div>
            <x-elements.primary-button type="submit">
                {{ isset($digitalMonster) ? 'Update' : 'Create' }} Monster
            </x-elements.primary-button>
        </form>
        @if(isset($digitalMonster))
        <div class="flex mt-4">
            <form method="POST" action="{{ route('digitalMonsters.destroy', $digitalMonster->id) }}" onsubmit="return confirm('Are you sure you want to delete this monster?');" class="ml-auto">
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