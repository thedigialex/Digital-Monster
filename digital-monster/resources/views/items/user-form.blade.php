<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($inventoryItem) ? 'Update Inventory Item' : 'Assign Inventory Item' }}
            </x-fonts.sub-header>
            <a href="{{ route('user.profile', ['id' => $user->id]) }}">
                <x-primary-button>
                    Go Back <i class="fa fa-arrow-left ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>
    <x-container>
        <form action="{{ route('user.inventory.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($inventoryItem))
            <input type="hidden" name="id" value="{{ $inventoryItem->id }}">
            @endif
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/4 mx-auto">
                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-inputs.label for="item_id">Item</x-inputs.input-label>
                                <x-inputs.dropdown id="item_id" name="item_id" class="form-control w-full" required onchange="toggleIsEquipped()">
                                    <option value="" {{ !isset($inventoryItem) }}>Select Item</option>
                                    @foreach($allItems as $item)
                                    <option value="{{ $item->id }}" data-type="{{ $item->type }}"
                                        {{ isset($inventoryItem) && $inventoryItem->item_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </x-inputs.dropdown>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="flex space-x-4">
                                <div class="w-full lg:w-1/2">
                                    <x-inputs.label for="quantity">Quantity</x-inputs.input-label>
                                        <x-inputs.text type="number" name="quantity" id="quantity" class="form-control w-full" value="{{ isset($inventoryItem) ? $inventoryItem->quantity : 1 }}" required></x-inputs.text>
                                </div>
                                <div class="w-full lg:w-1/2" id="isEquippedDiv">
                                    <x-inputs.label for="isEquipped">Equipped</x-inputs.input-label>
                                        <x-inputs.dropdown name="isEquipped" id="isEquipped" class="form-control w-full" required>
                                            <option value="1" {{ isset($inventoryItem) && $inventoryItem->isEquipped == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ isset($inventoryItem) && $inventoryItem->isEquipped == 0 ? 'selected' : '' }}>No</option>
                                        </x-inputs.dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($inventoryItem) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i></x-primary-button>
            </div>
        </form>
    </x-container>

    <script>
        function toggleIsEquipped() {
            const itemDropdown = document.getElementById('item_id');
            const isEquippedDiv = document.getElementById('isEquippedDiv');
            const selectedOption = itemDropdown.options[itemDropdown.selectedIndex];
            const itemType = selectedOption.getAttribute('data-type');
            isEquippedDiv.classList.add("hidden");
            if (itemType != 'Consumable' && itemType != 'Material' && itemType != null) {
                isEquippedDiv.classList.remove("hidden");
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleIsEquipped();
        });
    </script>
</x-app-layout>