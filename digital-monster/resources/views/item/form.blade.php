<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($item) ? 'Update Item' : 'Create Item' }}
        </x-fonts.sub-header>
        <a href="{{ route('items.index') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
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
                                divClasses="w-full"
                                value="{{ old('name', isset($item) ? $item->name : '') }}"
                                :messages="$errors->get('name')" />
                            <div class="w-full lg:w-1/2 flex space-x-4">
                                <x-inputs.text
                                    name="price"
                                    type="number"
                                    divClasses="w-full lg:w-1/2"
                                    value="{{ old('price', isset($item) ? $item->price : '') }}"
                                    :messages="$errors->get('price')" />
                                <x-inputs.text
                                    name="max_quantity"
                                    type="number"
                                    divClasses="w-full lg:w-1/2"
                                    value="{{ old('max_quantity', isset($item) ? $item->max_quantity : '') }}"
                                    :messages="$errors->get('max_quantity')" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4">
                            <x-inputs.dropdown
                                name="rarity"
                                divClasses="w-full lg:w-1/3"
                                :options="$rarityTypes"
                                :value="old('rarity', isset($item) ? $item->rarity : '')"
                                :messages="$errors->get('rarity')" />
                            <x-inputs.dropdown
                                name="type"
                                divClasses="w-full lg:w-1/3"
                                onchange="toggleEffectField()"
                                :options="$itemTypes"
                                :value="old('type', isset($item) ? $item->type : '')"
                                :messages="$errors->get('type')" />
                            <x-inputs.dropdown
                                name="available"
                                divClasses="w-full lg:w-1/3"
                                :options="['1' => 'Yes', '0' => 'No']"
                                useOptionKey="true"
                                :value="old('available', isset($item) ? $item->available : '')"
                                :messages="$errors->get('available')" />
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4" id="effectField">
                            <x-inputs.text
                                name="effect"
                                divClasses="w-full"
                                value="{{ old('effect', isset($item) ? $item->effect : '') }}" />
                        </div>

                <x-inputs.file label="Choose an Image" name="image" :currentImage="$item->image ?? null" :messages="$errors->get('image')" />
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary label="{{ isset($item) ? 'Update' : 'Create' }}" icon="fa-save" />
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