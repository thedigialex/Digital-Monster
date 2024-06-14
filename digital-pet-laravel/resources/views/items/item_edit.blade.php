<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ isset($item) ? 'Edit Item' : 'Create Item' }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        <form method="POST" action="{{ route('items.handle', isset($item) ? $item->id : null) }}" enctype="multipart/form-data">
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
                    <x-select-input id="type" name="type" :options="['Case' => 'Case', 'Background' => 'Background', 'Usable' => 'Usable']" :selected="$item->type ?? ''" required></x-select-input>
                </div>
                <div class="w-1/4">
                    <x-input-label for="available">Available:</x-input-label>
                    <x-select-input id="available" name="available" :options="[1 => 'Yes', 0 => 'No']" :selected="$item->available ?? ''" required></x-select-input>
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