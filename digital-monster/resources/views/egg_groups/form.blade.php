<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($eggGroup) ? 'Update Egg Group' : 'Create Egg Group' }}
        </x-fonts.sub-header>
        <a href="{{ route('egg_groups.index') }}">
            <x-primary-button icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error>
        Saving data, please fix fields.
    </x-alerts.error>
    @endif

    <x-container class="p-4">
        @if (isset($eggGroup))
        <x-forms.delete-form :action="route('egg_group.destroy')" label="Egg Group" />
        @endif
        <form action="{{ route('egg_group.update') }}" method="POST" class="space-y-4">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4 w-full">
                    <x-inputs.text
                        name="name"
                        divClasses="w-full"
                        value="{{ old('name', isset($eggGroup) ? $eggGroup->name : '') }}"
                        required />
                    <x-inputs.dropdown
                        name="field"
                        divClasses="w-full"
                        required
                        :options="$fields"
                        :value="old('field', isset($eggGroup) ? $eggGroup->field : '')" />
                </div>
                <div class="flex justify-center py-4">
                    <x-primary-button type="submit" label="{{ isset($eggGroup) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>