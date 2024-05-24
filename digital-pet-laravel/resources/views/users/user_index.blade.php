<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-bold">Users</h1>
                </div>

                @if ($users->isEmpty())
                <p class="text-gray-700">No users available.</p>
                @else
                <div class="relative p-4">
                    <table class="min-w-full bg-white text-center border border-gray-200 mb-8 pb-8">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-lg font-bold">ID</th>
                                <th class="py-2 px-4 border-b text-left text-lg font-bold">Name</th>
                                <th class="py-2 px-4 border-b text-left text-lg font-bold">Email</th>
                                <th class="py-2 px-4 border-b text-left text-lg font-bold">Nickname</th>
                                <th class="py-2 px-4 border-b text-left text-lg font-bold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr class="border-t">
                                <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                                <td class="py-2 px-4 border-b">{{ $user->nickname }}</td>
                                <td class="py-2 px-4 border-b">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('user.show', $user->id) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-md">View</a>
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
