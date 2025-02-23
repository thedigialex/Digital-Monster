<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($eggGroup) ? 'Update Egg Group' : 'Create Egg Group' }}
        </x-fonts.sub-header>
        <a href="{{ route('egg_groups.index') }}">
            <x-primary-button icon="fa-arrow-left">
                Go Back
            </x-primary-button>
        </a>
    </x-slot>

    <x-container class="p-4">
        <div class="flex justify-end w-full">
            @if (isset($eggGroup))
            <form action="{{ route('egg_groups.destroy', ['eggGroup' => $eggGroup->id]) }}"
                method="POST"
                class="inline"
                onsubmit="return confirm('Are you sure you want to delete this egg group?');">
                @csrf
                @method('DELETE')
                <x-danger-button icon="fa-trash">
                    Delete
                </x-danger-button>
            </form>
            @endif
        </div>
        <form action="{{ route('egg_groups.update') }}" method="POST" class="space-y-4">
            @csrf
            @if (isset($eggGroup))
            <input type="hidden" name="id" value="{{ $eggGroup->id }}">
            @endif
            <x-container.single>
                <x-inputs.text
                    name="name"
                    divClasses="w-full"
                    value="{{ isset($eggGroup) ? $eggGroup->name : '' }}"
                    required />
                <x-inputs.dropdown name="field_type" id="field_type" class="form-control w-full" required label="Field Type">
                    <option value="" disabled {{ isset($eggGroup) && !$eggGroup->field_type ? 'selected' : '' }}>Select Field Type</option>
                    @foreach($fieldTypes as $index => $label)
                    <option value="{{ $label }}" {{ isset($eggGroup) && $eggGroup->field_type == $label ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </x-inputs.dropdown>
                <div class="flex justify-center">
                    <x-primary-button type="submit">{{ isset($eggGroup) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i> </x-primary-button>
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>