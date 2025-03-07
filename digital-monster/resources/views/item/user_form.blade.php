<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userItem) ? 'Update User Item' : 'Assign User Item' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header class="text-accent">
                    {{ isset($userItem) ? 'Update User Item' : 'Assign User Item' }}
                </x-fonts.sub-header>
                @if (isset($userItem))
                <x-forms.delete-form :action="route('user.item.destroy')" label="Item" />
                @endif
            </div>
        </x-slot>

        <form action="{{ route('user.item.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.dropdown
                        name="item_id"
                        divClasses="w-full"
                        :messages="$errors->get('item_id')"
                        onchange="toggleEquip()"
                        :options="$allItems->pluck('name', 'id')->toArray()"
                        useOptionKey="true"
                        :data-items="$allItems->keyBy('id')->toArray()"
                        :value="old('item', isset($userItem) ? $userItem->item->id : '')" />
                    <x-inputs.text
                        name="quantity"
                        divClasses="w-full"
                        :messages="$errors->get('quantity')"
                        type="number"
                        :value="old('quantity', isset($userItem) ? $userItem->quantity : 1)" />
                    <div id="equipped_div" class="w-full">
                        <x-inputs.dropdown
                            name="equipped"
                            :options="['1' => 'Yes', '0' => 'No']"
                            useOptionKey="true"
                            :value="old('equipped', isset($userItem) ? $userItem->equipped : '')"
                            :messages="$errors->get('equipped')" />
                    </div>
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary type="submit" label="{{ isset($userItem) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>

    <script>
        function toggleEquip() {
            var itemDropdown = document.getElementById("item_id");
            var selectedOption = itemDropdown.options[itemDropdown.selectedIndex];
            var itemData = JSON.parse(selectedOption.getAttribute("data-item"));

            var equippedDiv = document.getElementById("equipped_div");
            if (itemData.type == "Material" || itemData.type == "Consumable") {
                equippedDiv.classList.add("hidden");
                equippedDiv.classList.remove("block");
            } else {
                equippedDiv.classList.add("block");
                equippedDiv.classList.remove("hidden");
            }
        }
    </script>
</x-app-layout>