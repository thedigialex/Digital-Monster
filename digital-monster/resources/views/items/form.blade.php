<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($item) ? 'Update Item' : 'Create Item' }}
            </x-fonts.sub-header>
            <a href="{{ route('items.index') }}" class="mt-4 lg:mt-0">
                <x-primary-button>
                    Go Back <i class="fa fa-arrow-left ml-2"></i>
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

            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/4 mx-auto">
                    <x-inputs.label for="image">Image</x-inputs.input-label>
                        @if (isset($item) && $item->image)
                        <div class="mt-2 w-32 h-32 overflow-hidden mx-auto">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                        </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control w-full mt-2" accept="image/*">
                </div>
                <div class="flex-1 space-y-4">
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
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($item) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i></x-primary-button>
            </div>
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