<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($item) ? 'Update Item' : 'Create Item' }}
        </x-fonts.sub-header>
        <a href="{{ route('items.index') }}">
            <x-primary-button icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error>
        Saving data, please fix fields.
    </x-alerts.error>
    @endif

    <x-container class="p-4">
        @if (isset($item))
        <x-forms.delete-form :action="route('items.destroy',  $item->id)" label="Item" />
        @endif
        <form action="{{ route('items.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.file label="Choose an Image" name="image" :currentImage="$item->image ?? null" />
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text
                                name="name"
                                divClasses="w-full"
                                value="{{ old('name', isset($item) ? $item->name : '') }}"
                                required />
                            <div class="w-full lg:w-1/2 flex space-x-4">
                                <x-inputs.text
                                    name="price"
                                    type="number"
                                    divClasses="w-full lg:w-1/2"
                                    value="{{ old('price', isset($item) ? $item->price : '') }}"
                                    required />
                                <x-inputs.dropdown
                                    name="isAvailable"
                                    divClasses="w-full lg:w-1/2"
                                    :options="['1' => 'Yes', '0' => 'No']"
                                    useOptionKey="true"
                                    :value="old('isAvailable', isset($item) ? $item->isAvailable : '')"
                                    required />
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <div class="w-full flex space-x-4">
                                <x-inputs.dropdown
                                    name="rarity"
                                    divClasses="w-full lg:w-1/2"
                                    required
                                    :options="$rarityTypes"
                                    :value="old('rarity', isset($item) ? $item->rarity : '')" />
                                <x-inputs.dropdown
                                    name="type"
                                    divClasses="w-full lg:w-1/2"
                                    onchange="toggleEffectField()"
                                    required
                                    :options="$itemTypes"
                                    :value="old('type', isset($item) ? $item->type : '')" />
                            </div>
                            <div class="w-full lg:w-1/2" id="effectField">
                                <x-inputs.text
                                    name="effect"
                                    divClasses="w-full"
                                    value="{{ old('effect', isset($item) ? $item->effect : '') }}"
                                    required />
                            </div>
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center">
                    <x-primary-button type="submit" label="{{ isset($item) ? 'Update' : 'Create' }}" icon="fa-save" />
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