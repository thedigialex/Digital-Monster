<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ __('Digital Monsters') }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        @if ($groupedMonsters->isEmpty())
        <p class="text-gray-700">No digital monsters available.</p>
        @else
        <div id="eggGroupContainer" class="relative p-4 overflow-x-hidden relative z-10 w-full mx-4">
            <x-table-header>
                <div class="flex items-center space-x-4">
                    <button id="prevButton">
                        &lt;
                    </button>
                    <button id="nextButton">
                        &gt;
                    </button>
                </div>
                <x-primary-button>
                    <a href="{{ route('monsters.create') }}">Add New Monster</a>
                </x-primary-button>
            </x-table-header>
            <x-table>
                @foreach ($groupedMonsters as $eggId => $monstersGroup)
                <tbody class="egg-group hidden">
                    <tr>
                        <td colspan="5" class="py-2 px-4 border-b text-left text-lg font-bold">Egg ID: {{ $eggId }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-4 border-b" style="width: 32%;">Sprite Sheet</td>
                        <td class="py-2 px-4 border-b" style="width: 32%;">Stage</td>
                        <td class="py-2 px-4 border-b" style="width: 32%;">Actions</td>
                    </tr>
                    @foreach ($monstersGroup as $monster)
                    <tr class="border-t">
                        <td class="py-2 px-4 border-b" style="width: 32%;">
                            <img src="{{ Storage::url($monster->sprite_sheet) }}" class="h-24 object-contain" alt="Current Sprite">
                        </td>
                        <td class="py-2 px-4 border-b" style="width: 32%;">{{ $monster->stage }}</td>
                        <td class="py-2 px-4 border-b" style="width: 32%;">
                            <x-secondary-button>
                                <a href="{{ route('monsters.edit', $monster->id) }}">Edit</a>
                            </x-secondary-button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @endforeach
            </x-table>
        </div>
        @endif
    </x-body-container>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const eggGroups = document.querySelectorAll('.egg-group');
        let currentIndex = 0;

        function showEggGroup(index) {
            eggGroups.forEach((group, i) => {
                group.classList.toggle('hidden', i !== index);
            });
        }
        document.getElementById('prevButton').addEventListener('click', function() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : eggGroups.length - 1;
            showEggGroup(currentIndex);
        });
        document.getElementById('nextButton').addEventListener('click', function() {
            currentIndex = (currentIndex < eggGroups.length - 1) ? currentIndex + 1 : 0;
            showEggGroup(currentIndex);
        });
        showEggGroup(currentIndex);
    });
</script>