<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-xl font-bold mb-4">{{ isset($digitalMonster) ? 'Edit Monster' : 'Create Monster' }}</h1>

                <form method="POST" action="{{ isset($digitalMonster) ? route('monsters.update', $digitalMonster->id) : route('monsters.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($digitalMonster))
                    @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="monster_id" class="block text-sm font-medium text-gray-700">Monster ID:</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="monster_id" name="monster_id" value="{{ $digitalMonster->monster_id ?? '' }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="egg_id" class="block text-sm font-medium text-gray-700">Egg ID:</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="egg_id" name="egg_id" value="{{ $digitalMonster->egg_id ?? '' }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="sprite_sheet" class="block text-sm font-medium text-gray-700">Sprite Sheet:</label>
                        <input type="file" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="sprite_sheet" name="sprite_sheet" {{ isset($digitalMonster) ? '' : 'required' }}>
                        @if(isset($digitalMonster) && $digitalMonster->sprite_sheet)
                        <a href="#" data-modal-toggle="imageModal">
                            <img src="{{ Storage::url($digitalMonster->sprite_sheet) }}" class="h-24 w-auto mt-2 cursor-pointer" alt="Current Sprite">
                        </a>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="stage" class="block text-sm font-medium text-gray-700">Stage:</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="stage" name="stage" required>
                            <option value="">Select Stage</option>
                            <option value="Egg" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Egg') ? 'selected' : '' }}>Egg</option>
                            <option value="Fresh" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Fresh') ? 'selected' : '' }}>Fresh</option>
                            <option value="Child" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Child') ? 'selected' : '' }}>Child</option>
                            <option value="Rookie" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Rookie') ? 'selected' : '' }}>Rookie</option>
                            <option value="Champion" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Champion') ? 'selected' : '' }}>Champion</option>
                            <option value="Ultimate" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Ultimate') ? 'selected' : '' }}>Ultimate</option>
                            <option value="Final" {{ (isset($digitalMonster) && $digitalMonster->stage == 'Final') ? 'selected' : '' }}>Final</option>
                        </select>
                    </div>

                    <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="Data" {{ (isset($digitalMonster) && $digitalMonster->type == 'Data') ? 'selected' : '' }}>Data</option>
                            <option value="Virus" {{ (isset($digitalMonster) && $digitalMonster->type == 'Virus') ? 'selected' : '' }}>Virus</option>
                            <option value="Vaccine" {{ (isset($digitalMonster) && $digitalMonster->type == 'Vaccine') ? 'selected' : '' }}>Vaccine</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="min_weight" class="block text-sm font-medium text-gray-700">Minimum Weight:</label>
                        <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="min_weight" name="min_weight" value="{{ $digitalMonster->min_weight ?? '' }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="max_energy" class="block text-sm font-medium text-gray-700">Maximum Energy:</label>
                        <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="max_energy" name="max_energy" value="{{ $digitalMonster->max_energy ?? '' }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="required_evo_points" class="block text-sm font-medium text-gray-700">Required Evolution Points:</label>
                        <input type="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="required_evo_points" name="required_evo_points" value="{{ $digitalMonster->required_evo_points ?? '' }}" required>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md">
                        {{ isset($digitalMonster) ? 'Update' : 'Create' }} Monster
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(isset($digitalMonster) && $digitalMonster->sprite_sheet)
    <div id="imageModal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center min-h-screen px-4">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 w-auto max-w-2xl">
            <div class="flex justify-between items-start p-4 rounded-t border-b dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Sprite Sheet
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="imageModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6">
                <img src="{{ Storage::url($digitalMonster->sprite_sheet) }}" alt="Current Sprite" class="max-w-full h-auto">
            </div>
        </div>
    </div>
    @endif
</x-app-layout>

<script>
    document.querySelectorAll('[data-modal-toggle]').forEach(function(modalToggle) {
        modalToggle.addEventListener('click', function() {
            const targetModal = document.getElementById(this.getAttribute('data-modal-toggle'));
            targetModal.classList.toggle('hidden');
        });
    });
</script>