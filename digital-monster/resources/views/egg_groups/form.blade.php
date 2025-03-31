<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($eggGroup) ? 'Update Egg Group' : 'Create Egg Group' }}
        </x-fonts.sub-header>
        <a href="{{ route('egg_groups.index') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($eggGroup) ? 'Update Egg Group' : 'Create Egg Group' }}
            </x-fonts.sub-header>
            @if (isset($eggGroup))
            <x-forms.delete-form :action="route('egg_group.destroy')" label="Egg Group" />
            @endif
        </x-slot>

        <form action="{{ route('egg_group.update') }}" method="POST">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4 w-full">
                    <x-inputs.text
                        name="name"
                        class="w-full"
                        value="{{ old('name', isset($eggGroup) ? $eggGroup->name : '') }}"
                        :messages="$errors->get('name')" />
                    <x-inputs.dropdown
                        name="field"
                        class="w-full"
                        :options="$fields"
                        :value="old('field', isset($eggGroup) ? $eggGroup->field : '')"
                        :messages="$errors->get('field')" />
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary label="{{ isset($eggGroup) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>