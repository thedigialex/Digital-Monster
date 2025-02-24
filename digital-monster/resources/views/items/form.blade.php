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

    @if ($errors->any())
    <x-slot name="alert">
        <x-alerts.error>
            Saving data, please fix fields.
        </x-alerts.error>
    </x-slot>
    @endif

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
                        <x-inputs.file label="Choose an Image" name="image" :currentImage="$item->image ?? null" />
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text
                                name="name"
                                divClasses="w-full"
                                value="{{ isset($item) ? $item->name : '' }}"
                                required />
                            <div class="w-full lg:w-1/2 flex space-x-4">
                                <x-inputs.text
                                    name="price"
                                    type="number"
                                    divClasses="w-full lg:w-1/2"
                                    value="{{ isset($item) ? $item->price : '' }}"
                                    required />
                                <x-inputs.dropdown
                                    name="isAvailable"
                                    divClasses="w-full lg:w-1/2"
                                    :options="['1' => 'Yes', '0' => 'No']"
                                    useOptionKey="true"
                                    :value="isset($item) ? ($item->isAvailable == 1 ? 'Yes' : 'No') : ''"
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
                                    :value="isset($item) ? $item->rarity : ''" />
                                <x-inputs.dropdown
                                    name="type"
                                    divClasses="w-full lg:w-1/2"
                                    onchange="toggleEffectField()"
                                    required
                                    :options="$itemTypes"
                                    :value="isset($item) ? $item->type : ''" />
                            </div>
                            <div class="w-full lg:w-1/2" id="effectField">
                                <x-inputs.text
                                    name="effect"
                                    divClasses="w-full"
                                    value="{{ isset($item) ? $item->effect : '' }}"
                                    required />
                            </div>
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center">
                    <x-primary-button type="submit" icon="fa-save">
                        {{ isset($item) ? 'Update' : 'Create' }}
                    </x-primary-button>
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