<x-app-layout>
    <script src="{{ asset('js/switch-tab.js') }}"></script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Items') }}
            </x-fonts.sub-header>
            <a href="{{ route('items.edit') }}">
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
            @foreach($itemTypes as $type)
            <button onclick="switchTab(event, 'tab-{{ $type }}')"
                class="tablinks {{ $loop->first ? 'active bg-primary' : 'bg-secondary' }} w-64 py-2 text-text font-semibold rounded-t-md hover:bg-primary">
                {{ ucfirst($type) }}
            </button>
            @endforeach
        </div>

        @foreach($itemTypes as $type)
        <div id="tab-{{ $type }}" class="tabcontent {{ !$loop->first ? 'hidden' : '' }}">
            @if(isset($items[$type]) && $items[$type]->isNotEmpty())
            <table class="min-w-full border border-primary border-4">
                <thead class="bg-primary">
                    <tr>
                        <th class="w-1/6 px-4 py-2 text-left text-text">Image</th>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                        <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items[$type] as $item)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <td class="px-4 py-2">
                            @if (isset($item->image))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-text">
                            <span class="font-bold">Available:</span> {{ $item->isAvailable == 1 ? 'Yes' : 'No' }}
                            <span class="font-bold ml-4">Name:</span> {{ $item->name }}
                            <span class="font-bold ml-4">Rarity:</span> {{ $item->rarity }}
                            <span class="font-bold ml-4">Price:</span> {{ $item->price }}
                        </td>
                        <td class="px-4 py-2 text-end space-x-4">
                            <a href="{{ route('items.edit', ['id' => $item->id]) }}">
                                <x-primary-button>
                                    Edit <i class="fa fa-edit ml-2"></i>
                                </x-primary-button>
                            </a>
                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
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
            <p class="text-text py-4">No items available in this category.</p>
            @endif
        </div>
        @endforeach
    </x-container>
</x-app-layout>