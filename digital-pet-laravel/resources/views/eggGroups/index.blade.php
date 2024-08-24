<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Manage Egg Groups
        </x-fonts.sub-header>
    </x-slot>

    <x-elements.container>
        @if(session('success'))
        <div class="mb-4 text-green-500">
            {{ session('success') }}
        </div>
        @endif
        <x-tables.table-header>
            <form method="POST" action="{{ isset($eggGroup) ? route('eggGroups.update', $eggGroup->id) : route('eggGroups.store') }}" class="flex items-center w-full">
                @csrf
                @if(isset($eggGroup))
                @method('PUT')
                @endif

                <div class="flex-grow">
                    <x-input-label for="name">Egg Group Name:</x-input-label>
                    <x-text-input id="name" name="name" value="{{ $eggGroup->name ?? '' }}" required></x-text-input>
                </div>

                <div class="ml-4">
                    <x-elements.primary-button type="submit">
                        {{ isset($eggGroup) ? 'Update' : 'Add' }} Egg Group
                    </x-elements.primary-button>
                </div>
            </form>
        </x-tables.table-header>
        <div x-data="{ open: false, selectedGroup: {} }">
            <template x-if="open">
                <x-elements.modal :title="'Edit '">
                    <form :action="'{{ url('eggGroups') }}/' + selectedGroup.id" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" x-model="selectedGroup.name" class="w-full p-2 border rounded" />
                        <x-elements.primary-button type="submit">Update</x-elements.primary-button>
                    </form>
                </x-elements.modal>
            </template>

            <x-tables.table>
                <tr class="bg-secondary">
                    <td class="py-2 px-4 font-bold" style="width: 85%;">
                        <x-fonts.paragraph>Name</x-fonts.paragraph>
                    </td>
                    <td class="py-2 px-4 font-bold" style="width: 15%;">
                        <x-fonts.paragraph>Actions</x-fonts.paragraph>
                    </td>
                </tr>
                <tbody id="eggGroupTable">
                    @foreach ($eggGroups as $group)
                    <tr class="egg-group-row" data-egg-group="{{ $group->id }}">
                        <td class="py-2 px-4" style="width: 85%;">
                            <x-fonts.paragraph id="groupName-{{ $group->id }}">{{ $group->name }}</x-fonts.paragraph>
                        </td>
                        <td class="py-2 px-4" style="width: 15%;">
                            <x-elements.secondary-button
                                @click="open = true; selectedGroup = { id: {{ $group->id }}, name: '{{ $group->name }}' }">Edit</x-elements.secondary-button>

                            <form action="{{ route('eggGroups.destroy', $group->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this egg group?');">
                                @csrf
                                @method('DELETE')
                                <x-delete-button type="submit">
                                    Delete
                                </x-delete-button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </x-tables.table>
        </div>

    </x-elements.container>



</x-app-layout>