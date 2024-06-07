<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ isset($item) ? 'Edit Item' : 'Create Item' }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        <form method="POST" action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($item))
            @method('PUT')
            @endif
            <div class="mb-4 flex space-x-4">
                <div class="w-1/4">
                    <x-input-label for="name">Item Name:</x-input-label>
                    <x-text-input id="name" name="name" value="{{ $item->name ?? '' }}" required></x-text-input>
                </div>
                <div class="w-1/4">
                    <x-input-label for="price">Price:</x-input-label>
                    <x-text-input type="number" id="price" name="price" value="{{ $item->price ?? '' }}" required></x-text-input>
                </div>
                <div class="w-1/4">
                    <x-input-label for="type">Type:</x-input-label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="type" name="type" required>
                        <option value="">Select type</option>
                        <option value="Case" {{ (isset($item) && $item->type == 'Case') ? 'selected' : '' }}>Case</option>
                        <option value="Background" {{ (isset($item) && $item->type == 'Background') ? 'selected' : '' }}>Background</option>
                        <option value="Usable" {{ (isset($item) && $item->type == 'Usable') ? 'selected' : '' }}>Usable</option>
                    </select>
                </div>
                <div class="w-1/4">
                    <label for="available" class="block text-sm font-medium text-gray-700">Available:</label>
                    <input type="checkbox" class="mt-1 block leading-tight" id="available" name="available" value="1" {{ (isset($item) && $item->available) ? 'checked' : '' }}>
                    <span class="text-sm text-gray-600">Check if the item is available</span>
                </div>
            </div>
            <div class="mb-4">
                <x-input-label for="image">Item Image:</x-input-label>
                <input type="file" id="image" name="image" {{ isset($item) ? '' : 'required' }}>
                @if(isset($item) && $item->image)
                <img src="{{ Storage::url($item->image) }}" class="h-24 w-auto mt-2" alt="Current Image">
                @endif
            </div>
            <x-primary-button type="submit">
                {{ isset($item) ? 'Update' : 'Create' }} Item
            </x-primary-button>
        </form>
        @if(isset($item))
        <div class="flex mt-4">
            <form method="POST" action="{{ route('items.destroy', $item->id) }}" onsubmit="return confirm('Are you sure you want to delete this item?');" class="ml-auto">
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