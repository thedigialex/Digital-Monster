<x-app-layout>
<script src="{{ asset('js/switch-tab.js') }}"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Digital Monsters') }}
            </x-fonts.sub-header>
            <a href="{{ route('digital_monsters.edit') }}">
                <x-primary-button>
                    Add New
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <x-alert-success>{{ session('success') }}</x-alert-success>
    @endif

    <x-container>
        <div class="flex justify-center space-x-4 bg-accent pt-4 rounded-t-md">
            @foreach($eggGroups as $index => $eggGroup)
            <button onclick="switchTab(event, 'tab-{{ $index }}')" class="tablinks {{ $index === 0 ? 'active bg-primary' : 'bg-secondary' }} w-64 py-2 text-text font-semibold rounded-t-md hover:bg-primary ">
                {{ $eggGroup->name }}
            </button>
            @endforeach
        </div>
        @foreach($eggGroups as $index => $eggGroup)
        <div id="tab-{{ $index }}" class="tabcontent {{ $index !== 0 ? 'hidden' : '' }} ">
            <table class="min-w-full border border-primary border-4">
                <thead class="bg-primary">
                    <tr>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Name</th>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Stage</th>
                        <th class="w-1/5 px-4 py-2 text-center text-text">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eggGroup->digitalMonsters as $digitalMonster)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <td class="px-4 py-2 text-text">{{ $digitalMonster->name }}</td>
                        <td class="px-4 py-2 text-text">{{ $digitalMonster->stage }}</td>
                        <td class="px-4 py-2 flex justify-center items-center space-x-2">
                            <a href="{{ route('digital_monsters.edit', ['id' => $digitalMonster->id]) }}">
                                <x-primary-button>Edit</x-primary-button>
                            </a>
                            <form action="{{ route('digital_monsters.destroy', $digitalMonster->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this digital monster?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Delete</x-danger-button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </x-container>
</x-app-layout>