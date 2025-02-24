<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Egg Groups') }}
        </x-fonts.sub-header>
        <a href="{{ route('egg_groups.edit') }}">
            <x-primary-button icon="fa-plus">
                Add New
            </x-primary-button>
        </a>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        @foreach ($fieldTypes as $index => $label)
        <x-accordion title="{{ $label }}" :open="$loop->first" :icon="$icons[$index]">
            @if (isset($eggGroups[$label]) && $eggGroups[$label]->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-2/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eggGroups[$label] as $eggGroup)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-2/3">
                            <x-fonts.paragraph class="font-bold text-text">{{ $eggGroup->name }}</x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3 text-end">
                            <a href="{{ route('egg_groups.edit', ['id' => $eggGroup->id]) }}">
                                <x-primary-button icon="fa-edit">
                                    Edit
                                </x-primary-button>
                            </a>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No egg groups available for this field type.</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>