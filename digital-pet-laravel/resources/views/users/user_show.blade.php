<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-xl font-bold mb-4">{{ $user->name }}'s Profile</h1>

                <div class="mb-6">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Nickname:</strong> {{ $user->nickname }}</p>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">User Digital Monsters</h2>
                    <a href="{{ route('user.createMonster', $user->id) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-md">New Monster</a>
                </div>

                @if($user->userDigitalMonsters->isEmpty())
                <p>No digital monsters available.</p>
                @else
                <div class="relative p-4">
                    <table class="min-w-full bg-white text-center border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Name</th>
                                <th class="py-2 px-4 border-b text-left">Egg ID</th>
                                <th class="py-2 px-4 border-b text-left">Stage</th>
                                <th class="py-2 px-4 border-b text-left">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->userDigitalMonsters as $monster)
                            <tr class="{{ $monster->isMain ? 'bg-blue-100' : '' }}">
                                <td class="py-2 px-4 border-b">{{ $monster->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $monster->egg_id }}</td>
                                <td class="py-2 px-4 border-b">{{ $monster->stage }}</td>
                                <td class="py-2 px-4 border-b">{{ $monster->type }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Items Owned by the User -->
                <h2 class="text-lg font-bold mt-8 mb-2">Items</h2>
                @if($user->inventory->isEmpty())
                <p>No items available.</p>
                @else
                <div class="relative p-4">
                    <table class="min-w-full bg-white text-center border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Item Name</th>
                                <th class="py-2 px-4 border-b text-left">Description</th>
                                <th class="py-2 px-4 border-b text-left">Quantity</th>
                                <th class="py-2 px-4 border-b text-left">Equipped</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->inventory as $inventory)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $inventory->item->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $inventory->item->description }}</td>
                                <td class="py-2 px-4 border-b">{{ $inventory->quantity }}</td>
                                <td class="py-2 px-4 border-b">{{ $inventory->is_equipped ? 'Yes' : 'No' }}</td>
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