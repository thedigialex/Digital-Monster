<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($item) ? 'Edit Item' : 'Create Item' }}
        </x-fonts.sub-header>
    </x-slot>

    <x-elements.container>
        <form method="POST" action="{{ route('items.handle', isset($item) ? $item->id : null) }}" enctype="multipart/form-data">
            @csrf
            @if(isset($item))
            @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div>
                    <x-input-label for="name">Item Name:</x-input-label>
                    <x-text-input id="name" name="name" value="{{ $item->name ?? '' }}" required></x-text-input>
                </div>

                <div>
                    <x-input-label for="price">Cost:</x-input-label>
                    <x-text-input type="number" id="price" name="price" value="{{ $item->price ?? '' }}" required></x-text-input>
                </div>

                <div>
                    <x-input-label for="type">Type:</x-input-label>
                    <x-elements.select-input id="type" name="type" :options="['Attack' => 'Attack', 'Background' => 'Background', 'Case' => 'Case', 'Usable' => 'Usable']" :selected="$item->type ?? ''" required></x-elements.select-input>
                </div>

                <div>
                    <x-input-label for="available">Available:</x-input-label>
                    <x-elements.select-input id="available" name="available" :options="[1 => 'Yes', 0 => 'No']" :selected="$item->available ?? ''" required></x-elements.select-input>
                </div>

                <div>
                    <x-input-label for="rarity">Rarity:</x-input-label>
                    <x-elements.select-input id="rarity" name="rarity" :options="['free' => 'Free', 'common' => 'Common', 'uncommon' => 'Uncommon', 'rare' => 'Rare', 'legendary' => 'Legendary']" :selected="$item->rarity ?? 'common'" required></x-elements.select-input>
                </div>

                <div>
                    <x-input-label for="image">Item Image:</x-input-label>
                    <input type="file" id="image" name="image" {{ isset($item) ? '' : 'required' }}>
                    @if(isset($item) && $item->image)
                    <img src="{{ Storage::url($item->image) }}" class="h-24 w-auto mt-2" alt="Current Image">
                    @endif
                </div>
            </div>
            <div class="flex justify-center mt-6">
                <x-elements.primary-button type="submit">
                    {{ isset($item) ? 'Update' : 'Create' }} Item
                </x-elements.primary-button>
            </div>
        </form>

        @if(isset($item))
        <div class="flex justify-end mt-6">
            <form method="POST" action="{{ route('items.destroy', $item->id) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
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