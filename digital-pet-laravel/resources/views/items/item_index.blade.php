<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ __('Items') }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        @if ($items->isEmpty())
        <x-paragraph>No items available.</x-paragraph>
        <a href="{{ route('items.create') }}">
            <x-primary-button>Add New Item</x-primary-button>
        </a>
        @else
        <x-table-header>
            <div class="flex items-center space-x-4"></div>
            <a href="{{ route('items.create') }}">
                <x-primary-button>Add New Item</x-primary-button>
            </a>
        </x-table-header>
        <x-table>
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border-b w-[10%] text-lg font-bold"><x-paragraph>Name</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Image</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Details</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Actions</x-paragraph></td>
            </tr>
            <tbody>
                @foreach ($items as $item)
                <tr class="border-t">
                    <td class="py-2 px-4 border-b w-[10%]"><x-paragraph>{{ $item->name }}</x-paragraph></td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <img src="{{ Storage::url($item->image) }}" class="h-24 object-contain" alt="Current Sprite">
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-paragraph>Available: {{ $item->available ? 'Yes' : 'No' }}</x-paragraph>
                        <x-paragraph>Price: {{ $item->price }}</x-paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <a href="{{ route('items.edit', $item->id) }}">
                            <x-secondary-button>Edit</x-secondary-button>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-table>
        @endif
    </x-body-container>
</x-app-layout>