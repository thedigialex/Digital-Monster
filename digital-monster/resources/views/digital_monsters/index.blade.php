<x-app-layout>
    <script src="{{ asset('js/switch-tab.js') }}"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Digital Monsters') }}
            </x-fonts.sub-header>
            <a href="{{ route('digital_monsters.edit') }}">
                <x-primary-button>
                    Add New <i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container>
        <x-slot name="header">
            @foreach($eggGroups as $index => $eggGroup)
            <button onclick="switchTab(event, 'tab-{{ $index }}')" class="tablinks {{ $index === 0 ? 'active bg-primary' : 'bg-secondary' }} w-64 py-2 text-text font-semibold rounded-t-md hover:bg-primary ">
                {{ $eggGroup->name }}
            </button>
            @endforeach
        </x-slot>
        @foreach($eggGroups as $index => $eggGroup)
        <div id="tab-{{ $index }}" class="tabcontent {{ $index !== 0 ? 'hidden' : '' }} ">
            <table class="min-w-full border border-primary border-4">
                <thead class="bg-primary">
                    <tr>
                        <th class="w-1/6 px-4 py-2 text-left text-text">Image</th>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                        <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eggGroup->digitalMonsters as $digitalMonster)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <td class="px-4 py-2">
                            @if (isset($digitalMonster->sprite_image_0))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $digitalMonster->sprite_image_0) }}" alt="Digtial Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-text">
                            <span class="font-bold">Stage:</span> {{ $digitalMonster->stage }}
                            <span class="font-bold ml-4">Name:</span> {{ $digitalMonster->name }}
                        </td>
                        <td class="px-4 py-2 text-end space-x-4">
                            <a href="{{ route('digital_monsters.edit', ['id' => $digitalMonster->id]) }}">
                                <x-primary-button>
                                    Edit <i class="fa fa-edit ml-2"></i>
                                </x-primary-button>
                            </a>
                            <form action="{{ route('digital_monsters.destroy', $digitalMonster->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this digital monster?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Delete <i class="fa fa-trash ml-2"></i> </x-danger-button>
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