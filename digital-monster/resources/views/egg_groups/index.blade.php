<x-app-layout>


    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Egg Groups') }}
            </x-fonts.sub-header>
            <a href="{{ route('egg_groups.edit') }}">
                <x-primary-button>
                    Add New <i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        @foreach ($fieldTypes as $index => $label)
        <x-accordion title="{{ $label }}" :open="$loop->first">
            @if (isset($eggGroups[$label]) && $eggGroups[$label]->isNotEmpty())
            <table class="min-w-full border border-primary border-4">
                <thead class="bg-primary">
                    <tr>
                        <th class="w-1/6 px-4 py-2 text-left text-text"></th>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                        <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eggGroups[$label] as $eggGroup)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <td class="px-4 py-2 text-text">
                            <div class="w-16 h-16 overflow-hidden"></div>
                        </td>
                        <td class="px-4 py-2 text-text">
                            <span class="font-bold">Name:</span> {{ $eggGroup->name }}
                        </td>
                        <td class="px-4 py-2 text-end space-x-4">
                            <a href="{{ route('egg_groups.edit', ['id' => $eggGroup->id]) }}">
                                <x-primary-button>
                                    Edit <i class="fa fa-edit ml-2"></i>
                                </x-primary-button>
                            </a>
                            <form action="{{ route('egg_groups.destroy', ['eggGroup' => $eggGroup->id]) }}"
                                method="POST"
                                class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this egg group?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Delete <i class="fa fa-trash ml-2"></i> </x-danger-button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <x-fonts.paragraph class="text-text p-4">No egg groups available for this field type.</x-fonts.paragraph>
            @endif
        </x-accordion>
        @endforeach
    </x-container>
</x-app-layout>