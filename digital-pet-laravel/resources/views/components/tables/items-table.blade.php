<x-tables.table-header>
    <div class="flex items-center space-x-4">
        @foreach ($itemTypes as $type)
        <button id="btn-{{ $type }}" onclick="filterItems('{{ $type }}')"
            class="px-4 py-2 -mb-px text-sm font-medium {{ request('type') === $type ? 'text-accent -2 border-accent' : 'text-text hover:text-accent' }}">
            {{ ucfirst($type) }}
        </button>
        @endforeach
    </div>

    <a href="{{ $route }}">
        <x-elements.primary-button>Add New</x-elements.primary-button>
    </a>
</x-tables.table-header>

<x-tables.table>
    <tr class="bg-secondary">
        <td class="py-2 px-4 font-bold" style="width: 15%;"><x-fonts.paragraph>Name</x-fonts.paragraph></td>
        <td class="py-2 px-4 font-bold" style="width: 55%;"><x-fonts.paragraph>Image</x-fonts.paragraph></td>
        <td class="py-2 px-4 font-bold" style="width: 15%;"><x-fonts.paragraph>Details</x-fonts.paragraph></td>
        <td class="py-2 px-4 font-bold" style="width: 15%;"><x-fonts.paragraph></x-fonts.paragraph></td>
    </tr>
    <tbody id="itemTable">
        @foreach ($items as $item)
        <tr class="item-row" data-type="{{ $item->type }}">
            <td class="py-2 px-4 " style="width: 15%;"><x-fonts.paragraph>{{ $item->name }}</x-fonts.paragraph></td>
            <td class="py-2 px-4 " style="width: 55%;">
                <img src="{{ Storage::url($item->image) }}" class="h-24 object-contain w-full" alt="Current Sprite">
            </td>
            <td class="py-2 px-4 " style="width: 15%;">
                <x-fonts.paragraph>Type: {{ $item->type }}</x-fonts.paragraph>
                <x-fonts.paragraph>Available: {{ $item->available ? 'Yes' : 'No' }}</x-fonts.paragraph>
                <x-fonts.paragraph>Price: {{ $item->price }}</x-fonts.paragraph>
            </td>
            <td class="py-2 px-4 " style="width: 15%;">
                @isset($user)
                <a href="{{ route('user.handleItem', [$user->id, $item->id]) }}">
                    <x-elements.secondary-button>Edit</x-elements.secondary-button>
                </a>
                @else
                <a href="{{ route('items.handle', ['id' => $item->id]) }}">
                    <x-elements.secondary-button>Edit</x-elements.secondary-button>
                </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</x-tables.table>

<script>
    function filterItems(type) {
        const rows = document.querySelectorAll('.item-row');
        const buttons = document.querySelectorAll('button[id^="btn-"]');
        
        rows.forEach(row => {
            if (type === 'All' || row.dataset.type === type) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        buttons.forEach(button => {
            button.classList.remove('text-accent', '-2', 'border-accent');
            button.classList.add('text-text', 'hover:text-accent');
        });
        
        const activeButton = document.getElementById('btn-' + type);
        activeButton.classList.remove('text-text', 'hover:text-accent');
        activeButton.classList.add('text-accent', '-2', 'border-accent');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const currentType = "{{ request('type') }}";
        if (currentType) {
            filterItems(currentType);
        } else {
            filterItems('All'); 
        }
    });
</script>
