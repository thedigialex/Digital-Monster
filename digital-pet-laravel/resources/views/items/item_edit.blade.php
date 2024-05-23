<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Items Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-xl font-bold mb-4">{{ isset($item) ? 'Edit Item' : 'Create Item' }}</h1>

                <form method="POST" action="{{ isset($item) ? route('items.update', $item->id) : route('items.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($item))
                    @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Item Name:</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="name" name="name" value="{{ $item->name ?? '' }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">type:</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="type" name="type" required>
                            <option value="">Select type</option>
                            <option value="Case" {{ (isset($item) && $item->type == 'Case') ? 'selected' : '' }}>Case</option>
                            <option value="Background" {{ (isset($item) && $item->type == 'Background') ? 'selected' : '' }}>Background</option>
                            <option value="Usable" {{ (isset($item) && $item->type == 'Usable') ? 'selected' : '' }}>Usable</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                        <input type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="price" name="price" value="{{ $item->price ?? '' }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="available" class="block text-sm font-medium text-gray-700">Available:</label>
                        <input type="checkbox" class="mt-1 block leading-tight" id="available" name="available" value="1" {{ $item->available ? 'checked' : '' }}>
                        <span class="text-sm text-gray-600">Check if the item is available</span>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md">
                        {{ isset($item) ? 'Update' : 'Create' }} Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>