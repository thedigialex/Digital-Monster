<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ isset($digitalMonster) ? 'Edit Monster' : 'Create Monster' }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        <form method="POST" action="{{ route('monsters.handle', $digitalMonster->id ?? null) }}" enctype="multipart/form-data">
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
                    <x-select-input id="stage" name="stage" :options="['Egg' => 'Egg', 'Fresh' => 'Fresh', 'Child' => 'Child', 'Rookie' => 'Rookie', 'Champion' => 'Champion', 'Ultimate' => 'Ultimate', 'Final' => 'Final']" :selected="$digitalMonster->stage ?? ''" required></x-select-input>
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
            <form method="POST" action="{{ route('monsters.destroy', $digitalMonster->id) }}" onsubmit="return confirm('Are you sure you want to delete this monster?');" class="ml-auto">
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