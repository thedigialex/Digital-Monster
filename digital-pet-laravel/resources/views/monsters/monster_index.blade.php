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

                @if ($monsters->isEmpty())
                    <p class="text-gray-700">No digital monsters available.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white text-center border border-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-2 px-4 border-b">
                                        <a href="{{ route('monsters.index', ['sort' => 'monster_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-center">
                                            Monster ID
                                            @if(request('sort') === 'monster_id')
                                                @if(request('direction') === 'asc')
                                                    <span class="ml-1">▲</span>
                                                @else
                                                    <span class="ml-1">▼</span>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th class="py-2 px-4 border-b">
                                        <a href="{{ route('monsters.index', ['sort' => 'egg_id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-center">
                                            Egg ID
                                            @if(request('sort') === 'egg_id')
                                                @if(request('direction') === 'asc')
                                                    <span class="ml-1">▲</span>
                                                @else
                                                    <span class="ml-1">▼</span>
                                                @endif
                                            @endif
                                        </a>
                                    </th>
                                    <th class="py-2 px-4 border-b">Stage</th>
                                    <th class="py-2 px-4 border-b">Type</th>
                                    <th class="py-2 px-4 border-b">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monsters as $monster)
                                    <tr class="border-t">
                                        <td class="py-2 px-4 border-b">{{ $monster->monster_id }}</td>
                                        <td class="py-2 px-4 border-b">{{ $monster->egg_id }}</td>
                                        <td class="py-2 px-4 border-b">{{ $monster->stage }}</td>
                                        <td class="py-2 px-4 border-b">{{ $monster->type }}</td>
                                        <td class="py-2 px-4 border-b flex justify-center space-x-2">
                                            <a href="{{ route('monsters.edit', $monster->id) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-md">Edit</a>
                                            <form method="POST" action="{{ route('monsters.destroy', $monster->id) }}" onsubmit="return confirm('Are you sure you want to delete this monster?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-md">Delete</button>
                                            </form>
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
