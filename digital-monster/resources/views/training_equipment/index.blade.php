<x-app-layout>
    <script src="{{ asset('js/switch-tab.js') }}"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Training Equipment') }}
            </x-fonts.sub-header>
            <a href="{{ route('trainingEquipments.edit') }}">
                <x-primary-button>
                    Add New <i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
    <x-alert-success>{{ session('success') }}</x-alert-success>
    @endif

    <x-container>
        <div class="flex justify-center space-x-4 bg-accent pt-4 rounded-t-md">
            @foreach ($stats as $index => $label)
            <button
                onclick="switchTab(event, 'tab-{{ $index }}')"
                class="tablinks {{ $loop->first ? 'active bg-primary' : 'bg-secondary' }} w-64 py-2 text-text font-semibold rounded-t-md hover:bg-primary">
                {{ $label }}
            </button>
            @endforeach
        </div>
        @foreach ($stats as $index => $label)
        <div id="tab-{{ $index }}" class="tabcontent {{ !$loop->first ? 'hidden' : '' }}">
            @if (isset($trainingEquipments[$label]) && $trainingEquipments[$label]->isNotEmpty())
            <table class="min-w-full border border-primary border-4">
                <thead class="bg-primary">
                    <tr>
                        <th class="w-1/6 px-4 py-2 text-left text-text">Image</th>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                        <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trainingEquipments[$label] as $equipment)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <td class="px-4 py-2">
                            @if (isset($equipment->image))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $equipment->image) }}" alt="Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-text"> 
                            <span class="font-bold">Name:</span> {{ $equipment->name }}
                        </td>
                        <td class="px-4 py-2 text-end space-x-4">
                            <a href="{{ route('trainingEquipments.edit', ['id' => $equipment->id]) }}">
                                <x-primary-button>
                                    Edit <i class="fa fa-edit ml-2"></i>
                                </x-primary-button>
                            </a>
                            <form action="{{ route('trainingEquipments.destroy', $equipment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this training equipment?');">
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
            <p class="text-text py-4">No equipment available for this stat.</p>
            @endif
        </div>
        @endforeach

    </x-container>

</x-app-layout>