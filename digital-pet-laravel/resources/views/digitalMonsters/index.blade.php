<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Digital Monsters') }}
        </x-fonts.sub-header>
    </x-slot>

    <x-elements.container>
        @if(empty($eggGroups))
            <div>
                <x-fonts.paragraph>
                    No egg groups available.
                </x-fonts.paragraph>
                <a href="{{ route('eggGroups.index') }}">
                    <x-elements.primary-button>Add Egg Group</x-elements.primary-button>
                </a>
            </div>
        @else
            <x-tables.table-header>
                <div class="flex items-center space-x-4">
                    <button id="btn-all" onclick="filterMonsters('all')"
                        class="px-4 py-2 -mb-px text-sm font-medium text-accent -2 border-accent">
                        All
                    </button>
                    @foreach ($eggGroups as $index => $group)
                    <button id="btn-{{ $index }}" onclick="filterMonsters('{{ $index }}')"
                        class="px-4 py-2 -mb-px text-sm font-medium {{ request('egg_group') == $index ? 'text-accent -2 border-accent' : 'text-text hover:text-accent' }}">
                        {{ $group }}
                    </button>
                    @endforeach
                </div>
                <a href="{{ route('digitalMonsters.handle') }}">
                    <x-elements.primary-button>Add New</x-elements.primary-button>
                </a>
            </x-tables.table-header>

            <x-tables.table>
                <tr class="bg-secondary">
                    <td class="py-2 px-4 font-bold" style="width: 15%;"><x-fonts.paragraph>Id</x-fonts.paragraph></td>
                    <td class="py-2 px-4 font-bold" style="width: 55%;"><x-fonts.paragraph>Image</x-fonts.paragraph></td>
                    <td class="py-2 px-4 font-bold" style="width: 15%;"><x-fonts.paragraph>Stage</x-fonts.paragraph></td>
                    <td class="py-2 px-4 font-bold" style="width: 15%;"><x-fonts.paragraph></x-fonts.paragraph></td>
                </tr>
                <tbody id="monsterTable">
                    @foreach ($digitalMonsters as $digitalMonster)
                    <tr class="monster-row" data-egg-group="{{ $digitalMonster->eggId }}">
                        <td class="py-2 px-4" style="width: 15%;"><x-fonts.paragraph>{{ $digitalMonster->monsterId }}</x-fonts.paragraph></td>
                        <td class="py-2 px-4" style="width: 55%;">
                            <img src="{{ Storage::url($digitalMonster->spriteSheet) }}" class="h-24 object-contain w-full" alt="Current Sprite">
                        </td>
                        <td class="py-2 px-4" style="width: 15%;"><x-fonts.paragraph>{{ $digitalMonster->stage }}</x-fonts.paragraph></td>
                        <td class="py-2 px-4" style="width: 15%;">
                            <a href="{{ route('digitalMonsters.handle', $digitalMonster->id) }}">
                                <x-elements.secondary-button>Edit</x-elements.secondary-button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </x-tables.table>
        @endif
    </x-elements.container>
</x-app-layout>

<script>
    function filterMonsters(eggGroup) {
        const rows = document.querySelectorAll('.monster-row');
        const buttons = document.querySelectorAll('button[id^="btn-"]');

        rows.forEach(row => {
            row.style.display = (eggGroup === 'all' || row.dataset.eggGroup == eggGroup) ? '' : 'none';
        });

        buttons.forEach(button => {
            button.classList.remove('text-accent', '-2', 'border-accent');
            button.classList.add('text-text', 'hover:text-accent');
        });

        const activeButton = document.getElementById('btn-' + eggGroup);
        activeButton.classList.remove('text-text', 'hover:text-accent');
        activeButton.classList.add('text-accent', '-2', 'border-accent');
    }

    document.addEventListener('DOMContentLoaded', function() {
        filterMonsters('all'); // Show all monsters by default
    });
</script>
