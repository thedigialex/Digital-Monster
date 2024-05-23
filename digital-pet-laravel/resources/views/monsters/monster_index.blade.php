<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Digital Monsters') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-bold">Digital Monsters</h1>
                    <a href="{{ route('monsters.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md">Add New Monster</a>
                </div>

                @if ($groupedMonsters->isEmpty())
                <p class="text-gray-700">No digital monsters available.</p>
                @else
                <div class="relative p-4">
                    <div id="eggGroupContainer" class="overflow-x-hidden relative z-10 w-full mx-4">
                        <table class="min-w-full bg-white text-center border border-gray-200 mb-8 pb-8">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th colspan="5" class="py-2 px-4 border-b text-left text-lg font-bold">
                                        <button id="prevButton" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none z-50">
                                            &lt;
                                        </button>
                                        <button id="nextButton" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-full shadow-lg hover:bg-blue-600 focus:outline-none z-50">
                                            &gt;
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            @foreach ($groupedMonsters as $eggId => $monstersGroup)
                            <tbody class="egg-group hidden">
                                <tr>
                                    <td colspan="5" class="py-2 px-4 border-b text-left text-lg font-bold">Egg ID: {{ $eggId }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">Sprite Sheet</td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">Stage</td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">Monster ID</td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">Type</td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">Actions</td>
                                </tr>
                                @foreach ($monstersGroup as $monster)
                                <tr class="border-t">
                                    <td class="py-2 px-4 border-b" style="width: 20%;">
                                        <a href="#" data-modal-toggle="imageModal" data-src="{{ Storage::url($monster->sprite_sheet) }}">
                                            <img src="{{ Storage::url($monster->sprite_sheet) }}" class="h-24 w-24 mt-2 object-cover" alt="Current Sprite">
                                        </a>
                                    </td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">{{ $monster->stage }}</td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">{{ $monster->monster_id }}</td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">{{ $monster->type }}</td>
                                    <td class="py-2 px-4 border-b" style="width: 20%;">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('monsters.edit', $monster->id) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-md">Edit</a>
                                            <form method="POST" action="{{ route('monsters.destroy', $monster->id) }}" onsubmit="return confirm('Are you sure you want to delete this monster?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>

                @endif
            </div>
        </div>
    </div>

    <div id="imageModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 w-auto max-w-2xl z-10">
            <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Sprite Sheet
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="imageModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <img id="modalImage" src="{{ Storage::url($monster->sprite_sheet) }}" alt="Current Sprite" class="w-64 h-64 object-cover">
            </div>
        </div>
    </div>
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
</script>