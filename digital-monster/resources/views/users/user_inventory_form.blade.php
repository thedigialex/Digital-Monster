<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($inventoryItem) ? 'Update Inventory Item' : 'Add New Inventory Item' }}
            </x-fonts.sub-header>
            <a href="{{ route('user.inventory', ['id' => $user->id]) }}">
                <x-primary-button>
                    Go Back
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-container>
        <form action="{{ route('user.inventory.update') }}" method="POST" class="space-y-4">
            @csrf
            @if (isset($inventoryItem))
            <input type="hidden" name="id" value="{{ $inventoryItem->id }}">
            @endif
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-inputs.label for="item_id">Item</x-inputs.input-label>
                    <x-inputs.dropdown id="item_id" name="item_id" class="form-control w-full" required onchange="toggleIsEquipped()">
                        <option value="" disabled {{ !isset($inventoryItem) ? 'selected' : '' }}>Select Item</option>
                        @foreach($allItems as $item)
                        <option value="{{ $item->id }}" data-type="{{ $item->type }}"
                            {{ isset($inventoryItem) && $inventoryItem->item_id == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                        @endforeach
                    </x-inputs.dropdown>
                </div>
                <div class="flex-1">
                    <x-inputs.label for="quantity">Quantity</x-inputs.input-label>
                    <x-inputs.text type="number" name="quantity" class="form-control w-full" value="{{ isset($inventoryItem) ? $inventoryItem->quantity : 1 }}" required></x-inputs.text>
                </div>
                <div class="flex-1" id="equippedContainer" style="display: {{ isset($inventoryItem) && !$inventoryItem->isConsumable ? 'block' : 'none' }}">
                    <x-inputs.label for="isEquipped">Is Equipped</x-inputs.input-label>
                    <input type="checkbox" name="isEquipped" id="isEquipped"
                        {{ isset($inventoryItem) && $inventoryItem->isEquipped ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($inventoryItem) ? 'Update' : 'Create' }}</x-primary-button>
            </div>
        </form>
    </x-container>

    <script>
        function toggleIsEquipped() {
            const itemDropdown = document.getElementById('item_id');
            const equippedContainer = document.getElementById('equippedContainer');
            const selectedOption = itemDropdown.options[itemDropdown.selectedIndex];
            const itemType = selectedOption.getAttribute('data-type');

            if (itemType === 'consumable') {
                equippedContainer.style.display = 'none';
            } else {
                equippedContainer.style.display = 'block';
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleIsEquipped();
        });
    </script>
</x-app-layout>