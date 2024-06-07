<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ __('Digital Monsters') }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        @if ($groupedMonsters->isEmpty())
        <x-paragraph>No digital monsters available.</x-paragraph>
        <a href="{{ route('monsters.create') }}">
            <x-primary-button>Add New Monster</x-primary-button>
        </a>
        @else
        <div id="eggGroupContainer">
            <x-table-header>
                <div class="flex items-center space-x-4">
                    <button id="prevButton">
                        &lt;
                    </button>
                    <button id="nextButton">
                        &gt;
                    </button>
                </div>
                <a href="{{ route('monsters.create') }}">
                    <x-primary-button>Add New Monster</x-primary-button>
                </a>
            </x-table-header>
            <x-table>
                @foreach ($groupedMonsters as $eggId => $monstersGroup)
                <tbody class="egg-group hidden">
                    <tr class="bg-gray-50">
                        <td class="py-2 px-4 border-b w-[10%] text-lg font-bold"><x-paragraph>Egg: {{ $eggId }}</x-paragraph></td>
                        <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Spirte</x-paragraph></td>
                        <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Details</x-paragraph></td>
                        <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Actions</x-paragraph></td>
                    </tr>
                    @foreach ($monstersGroup as $monster)
                    <tr class="border-t">
                        <td class="py-2 px-4 border-b w-[10%]"></td>
                        <td class="py-2 px-4 border-b w-[31%]">
                            <img src="{{ Storage::url($monster->sprite_sheet) }}" class="h-24 object-contain" alt="Current Sprite">
                        </td>
                        <td class="py-2 px-4 border-b w-[31%]">
                            <x-paragraph>Monster ID: {{ $monster->monster_id }}</x-paragraph>
                            <x-paragraph>Stage: {{ $monster->stage }}</x-paragraph>
                        </td>
                        <td class="py-2 px-4 border-b w-[31%]">
                            <a href="{{ route('monsters.edit', $monster->id) }}">
                                <x-secondary-button>Edit</x-secondary-button>
                            </a>
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