<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($item) ? 'Update Item' : 'Create Item' }}
        </x-fonts.sub-header>
        <a href="{{ route('items.index') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.alert type="Error" />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($item) ? 'Update Item' : 'Create Item' }}
            </x-fonts.sub-header>
            @if (isset($item))
            <x-forms.delete-form :action="route('item.destroy')" label="Item" />
            @endif
        </x-slot>

        <form action="{{ route('item.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.text
                        name="name"
                        class="w-full md:1/2"
                        value="{{ old('name', isset($item) ? $item->name : '') }}"
                        :messages="$errors->get('name')" />
                    <x-inputs.text
                        name="price"
                        type="number"
                        class="w-full md:w-1/4"
                        value="{{ old('price', isset($item) ? $item->price : '') }}"
                        :messages="$errors->get('price')" />
                    <x-inputs.text
                        name="max_quantity"
                        type="number"
                        class="w-full md:w-1/4"
                        value="{{ old('max_quantity', isset($item) ? $item->max_quantity : '') }}"
                        :messages="$errors->get('max_quantity')" />
                </div>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.dropdown
                        name="type"
                        class="w-full md:w-1/3"
                        onchange="toggleTypeDropdown(event)"
                        :options="$itemTypes"
                        :value="old('type', isset($item) ? $item->type : '')"
                        :messages="$errors->get('type')" />
                    <x-inputs.text
                        name="effect"
                        class="hidden w-full md:w-1/3"
                        value="{{ old('effect', isset($item) ? $item->effect : '') }}" />
                    <x-inputs.dropdown
                        name="rarity"
                        class="w-full md:w-1/3"
                        :options="$rarityTypes"
                        :value="old('rarity', isset($item) ? $item->rarity : '')"
                        :messages="$errors->get('rarity')" />
                    <x-inputs.dropdown
                        name="available"
                        class="w-full md:w-1/3"
                        :options="['1' => 'Yes', '0' => 'No']"
                        useOptionKey="true"
                        :value="old('available', isset($item) ? $item->available : '')"
                        :messages="$errors->get('available')" />
                </div>

                <div class="flex flex-col justify-center items-center py-4 space-y-4">
                    <div class="flex flex-col md:flex-row justify-center items-center gap-4 w-full md:1/3">
                        <x-inputs.file label="Choose an Image" name="image" class="w-1/3" :currentImage="$item->image ?? null" :messages="$errors->get('image')" />
                    </div>
                    <x-buttons.button type="edit" label="{{ isset($item) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        let dropdown = document.getElementById("type");
        if(dropdown.value === 'Consumable'){
            document.getElementById('effect_div').classList.remove('hidden');
        }
    });

    function toggleTypeDropdown(event) {
        document.getElementById('effect_div').classList.toggle('hidden', event.target.value !== 'Consumable');
    }
</script>