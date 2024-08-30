<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($item) ? 'Edit Inventory Item' : 'Add Inventory Item' }}
        </x-fonts.sub-header>
    </x-slot>
    <x-elements.container>
        <form method="POST" action="{{ isset($item) ? route('user.handleItem', [$user->id, $item->id]) : route('user.handleItem', $user->id) }}">
            @csrf
            <div class="mb-4 flex space-x-4">
                <div class="w-1/3">
                    <x-input-label for="item_id">Item</x-input-label>
                    <select id="item_id" name="item_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">Select an item</option>
                        @foreach($allItems as $itemOption)
                        <option value="{{ $itemOption->id }}" {{ $item && $item->item_id == $itemOption->id ? 'selected' : '' }}>
                            {{ $itemOption->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-1/3">
                    <x-input-label for="quantity">Quantity</x-input-label>
                    <x-text-input id="quantity" name="quantity" type="number" value="{{ $item ? $item->quantity : old('quantity') }}" required></x-text-input>
                </div>

                <div class="w-1/3">
                    <x-input-label for="is_equipped">Is Equipped</x-input-label>
                    <select id="is_equipped" name="is_equipped" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="1" {{ $item && $item->is_equipped ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $item && !$item->is_equipped ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            </div>

            <x-elements.primary-button type="submit">
                {{ isset($item) ? 'Update' : 'Add' }} Item
            </x-elements.primary-button>
        </form>

        @if (isset($item))
        <div class="flex mt-4">
            <form method="POST" action="{{ route('user.deleteItem', [$user->id, $item->id]) }}" onsubmit="return confirm('Are you sure you want to delete this item?');" class="ml-auto">
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