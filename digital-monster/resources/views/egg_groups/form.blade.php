<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($eggGroup) ? 'Update Egg Group' : 'Create Egg Group' }}
            </x-fonts.sub-header>
            <a href="{{ route('egg_groups.index') }}">
                <x-primary-button>
                    Go Back <i class="fa fa-arrow-left ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>
    <x-container>
        <form action="{{ route('egg_groups.update') }}" method="POST" class="space-y-4">
            @csrf
            @if (isset($eggGroup))
            <input type="hidden" name="id" value="{{ $eggGroup->id }}">
            @endif
            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/4 mx-auto">

                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-inputs.label for="name">Name</x-inputs.input-label>
                                <x-inputs.text type="text" name="name" id="name" class="form-control w-full" value="{{ isset($eggGroup) ? $eggGroup->name : '' }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <x-inputs.label for="field_type">Field Type</x-inputs.input-label>
                                <x-inputs.dropdown name="field_type" id="field_type" class="form-control w-full" required>
                                    <option value="" disabled {{ isset($eggGroup) && !$eggGroup->field_type ? 'selected' : '' }}>Select Field Type</option>
                                    @foreach($fieldTypes as $index => $label)
                                    <option value="{{ $label }}" {{ isset($eggGroup) && $eggGroup->field_type == $label ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                    @endforeach
                                </x-inputs.dropdown>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($trainingEquipment) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i> </x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>