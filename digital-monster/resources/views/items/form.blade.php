<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($item) ? 'Update Item' : 'Create Item' }}
        </x-fonts.sub-header>
        <a href="{{ route('items.index') }}">
            <x-primary-button icon="fa-arrow-left">
                Go Back
            </x-primary-button>
        </a>
    </x-slot>

    <x-container class="p-4">
        <div class="flex justify-end w-full">
            @if (isset($item))
            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit" icon="fa-trash">
                    Delete
                </x-danger-button>
            </form>
            @endif
        </div>

        <form action="{{ route('items.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($item))
            <input type="hidden" name="id" value="{{ $item->id }}">
            @endif
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.file label="Upload Image" name="image" id="image" class="mt-2" />

                        @if (isset($item) && $item->image)
                        <div class="mt-2 w-32 h-32 overflow-hidden mx-auto">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                        </div>
                        @endif

                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <div class="flex-1">
                                <x-inputs.label for="name">Name</x-inputs.input-label>
                                    <x-inputs.text type="text" name="name" id="name" class="form-control w-full" value="{{ isset($item) ? $item->name : '' }}" required></x-inputs.text>
                            </div>
                            <div class="w-full lg:w-1/4">
                                <div class="flex space-x-4">
                                    <div class="w-full lg:w-1/2">
                                        <x-inputs.label for="price">Price</x-inputs.input-label>
                                            <x-inputs.text type="number" name="price" id="price" class="form-control w-full" value="{{ isset($item) ? $item->price : '' }}" required></x-inputs.text>
                                    </div>
                                    <div class="w-full lg:w-1/2">
                                        <x-inputs.label for="isAvailable">Available</x-inputs.input-label>
                                            <x-inputs.dropdown name="isAvailable" id="isAvailable" class="form-control w-full" required>
                                                <option value="1" {{ isset($item) && $item->isAvailable == 1 ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ isset($item) && $item->isAvailable == 0 ? 'selected' : '' }}>No</option>
                                            </x-inputs.dropdown>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <div class="w-full lg:w-1/2">
                                <x-inputs.label for="rarity">Rarity</x-inputs.input-label>
                                    <x-inputs.dropdown name="rarity" id="rarity" class="form-control w-full" required>
                                        <option value="" disabled {{ isset($item) && !$item->rarity ? 'selected' : '' }}>Select a rarity</option>
                                        @foreach ($rarityTypes as $rarity)
                                        <option value="{{ $rarity }}" {{ isset($item) && $item->rarity === $rarity ? 'selected' : '' }}>
                                            {{ ucfirst($rarity) }}
                                        </option>
                                        @endforeach
                                    </x-inputs.dropdown>
                            </div>
                            <div class="w-full lg:w-1/2">
                                <x-inputs.label for="type">Type</x-inputs.input-label>
                                    <x-inputs.dropdown name="type" id="type" class="form-control w-full" required onchange="toggleEffectField()">
                                        <option value="" disabled {{ isset($item) && !$item->type ? 'selected' : '' }}>Select a type</option>
                                        @foreach ($itemTypes as $type)
                                        <option value="{{ $type }}" {{ isset($item) && $item->type === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                        @endforeach
                                    </x-inputs.dropdown>
                            </div>
                        </div>
                        <div id="effectField" class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0 hidden">
                            <div class="w-full lg:w-1/2">
                                <x-inputs.label for="effect">Effect</x-inputs.input-label>
                                    <x-inputs.text type="text" name="effect" id="effect" class="form-control w-full" value="{{ isset($item) ? $item->effect : '' }}"></x-inputs.text>
                            </div>
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center">
                    <x-primary-button type="submit">{{ isset($item) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i> </x-primary-button>
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>

<script>
    function toggleEffectField() {
        var type = document.getElementById("type").value;
        var effectField = document.getElementById("effectField");
        effectField.classList.add("hidden");
        if (type === 'Consumable') {
            effectField.classList.remove("hidden");
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        toggleEffectField();
    });
</script>