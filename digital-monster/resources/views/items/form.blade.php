<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($item) ? 'Update Item' : 'Create Item' }}
            </x-fonts.sub-header>
            <a href="{{ route('items.index') }}">
                <x-primary-button>
                    Go Back
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-container>
        <form action="{{ route('items.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($item))
            <input type="hidden" name="id" value="{{ $item->id }}">
            @endif
            <div>
                <x-input-label for="name">Name</x-input-label>
                <x-text-input type="text" name="name" class="form-control w-full" value="{{ isset($item) ? $item->name : '' }}" required></x-text-input>
            </div>
            <div>
                <x-input-label for="type">Type</x-input-label>
                <select name="type" id="type" class="form-control w-full" required>
                    <option value="" disabled {{ isset($item) && !$item->type ? 'selected' : '' }}>Select a type</option>
                    @foreach ($itemTypes as $value => $label)
                    <option value="{{ $value }}" {{ isset($item) && $item->type === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="effect">Effect</x-input-label>
                <x-text-input type="text" name="effect" class="form-control w-full" value="{{ isset($item) ? $item->effect : '' }}"></x-text-input>
            </div>
            <div>
                <x-input-label for="price">Price</x-input-label>
                <x-text-input type="number" name="price" class="form-control w-full" value="{{ isset($item) ? $item->price : '' }}" required></x-text-input>
            </div>
            <div>
                <x-input-label for="rarity">Rarity</x-input-label>
                <select name="rarity" id="rarity" class="form-control w-full" required>
                    <option value="0" {{ isset($item) && $item->rarity == 0 ? 'selected' : '' }}>Free</option>
                    <option value="1" {{ isset($item) && $item->rarity == 1 ? 'selected' : '' }}>Common</option>
                    <option value="2" {{ isset($item) && $item->rarity == 2 ? 'selected' : '' }}>Uncommon</option>
                    <option value="3" {{ isset($item) && $item->rarity == 3 ? 'selected' : '' }}>Rare</option>
                    <option value="4" {{ isset($item) && $item->rarity == 4 ? 'selected' : '' }}>Legendary</option>
                    <option value="5" {{ isset($item) && $item->rarity == 5 ? 'selected' : '' }}>Mythical</option>
                </select>
            </div>

            <!-- Image Upload Section -->
            <div>
                <x-input-label for="image">Image</x-input-label>
                <input type="file" name="image" id="image" class="form-control w-full" accept="image/*">
                @if (isset($item) && $item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="mt-2 w-32 h-32 object-cover">
                @endif
            </div>

            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($item) ? 'Update' : 'Create' }}</x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>