<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Items') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-bold">Items</h1>
                    <a href="{{ route('items.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md">Add New Item</a>
                </div>

                @if ($items->isEmpty())
                <p class="text-gray-700">No items available.</p>
                @else
                <div class="relative p-4">
                    <table class="min-w-full bg-white text-center border border-gray-200 mb-8 pb-8">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-lg font-bold">Item Name</th>
                                <th class="py-2 px-4 border-b">Available</th>
                                <th class="py-2 px-4 border-b">Price</th>
                                <th class="py-2 px-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                            <tr class="border-t">
                                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->available }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->price }}</td>
                                <td class="py-2 px-4 border-b">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('items.edit', $item->id) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-md">Edit</a>
                                        <form method="POST" action="{{ route('items.destroy', $item->id) }}" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
