<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($eggGroup) ? 'Update Egg Group' : 'Create Egg Group' }}
            </x-fonts.sub-header>
            <a href="{{ route('egg_groups.index') }}">
                <x-primary-button>
                    Go Back
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
            <div class="flex flex-col md:flex-row md:space-x-4">
                <div class="flex-1">
                    <x-input-label for="name">Name</x-input-label>
                    <x-text-input type="text" name="name" class="form-control w-full" value="{{ isset($eggGroup) ? $eggGroup->name : '' }}" required></x-text-input>
                </div>
                <div class="flex-1">
                    <x-input-label for="field_type">Field Type</x-input-label>
                    <x-text-dropdown name="field_type" class="form-control w-full" required>
                        <option value="" disabled {{ !isset($eggGroup) ? 'selected' : '' }}>Select Field Type</option>
                        @foreach($fieldTypes as $value => $label)
                        <option value="{{ $value }}" {{ isset($eggGroup) && $eggGroup->field_type == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </x-text-dropdown>
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($eggGroup) ? 'Update' : 'Create' }}</x-primary-button>
            </div>
        </form>
    </x-container>
</x-app-layout>