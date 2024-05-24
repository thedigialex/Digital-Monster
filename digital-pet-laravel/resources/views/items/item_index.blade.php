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
                                <th class="py-2 px-4 border-b">Image</th>
                                <th class="py-2 px-4 border-b">Available</th>
                                <th class="py-2 px-4 border-b">Price</th>
                                <th class="py-2 px-4 border-b">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                            <tr class="border-t">
                                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                <td class="py-2 px-4 border-b">
                                    <a href="#" data-modal-toggle="imageModal" data-src="{{ Storage::url($item->image) }}">
                                        <img src="{{ Storage::url($item->image) }}" class="h-24 w-24 mt-2 object-cover" alt="Item Image">
                                    </a>
                                </td>
                                <td class="py-2 px-4 border-b">{{ $item->available ? 'Yes' : 'No' }}</td>
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

                <div id="imageModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-black bg-opacity-50"></div>
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 w-auto max-w-2xl z-10">
                        <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Item Image
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="imageModal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <div class="p-6">
                            <img id="modalImage" src="" alt="Item Image" class="w-64 h-64 object-cover">
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-modal-toggle]').forEach(function(modalToggle) {
            modalToggle.addEventListener('click', function(event) {
                event.preventDefault();
                const targetModal = document.getElementById(this.getAttribute('data-modal-toggle'));
                const newSrc = this.getAttribute('data-src');
                const modalImage = targetModal.querySelector('img');
                if (newSrc) {
                    modalImage.src = newSrc;
                }
                targetModal.classList.toggle('hidden');
            });
        });
    });
</script>