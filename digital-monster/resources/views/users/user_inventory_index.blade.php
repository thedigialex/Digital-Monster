<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Users and their Inventory') }}
            </x-fonts.sub-header>
            <a href="{{ route('users.index') }}">
                <x-primary-button>
                    Go Back
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
        <x-alert-success>{{ session('success') }}</x-alert-success>
    @endif

    <x-container>
        <div class="mb-6">
            <h2 class="text-xl font-bold">{{ $user->name }}</h2>
            <p>{{ $user->email }}</p>
            <a href="{{ route('user.inventory.edit', ['userId' => $user->id]) }}" class="btn btn-primary">Add New Item</a>

            @if ($user->inventories->isEmpty())
            <p>No items found for this user.</p>
            @else
            <table class="min-w-full border-collapse border border-gray-200 mt-4">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-600">Item Name</th>
                        <th class="px-4 py-2 text-left text-gray-600">Quantity</th>
                        <th class="px-4 py-2 text-left text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->inventories as $item)
                    <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                        <td class="px-4 py-2 border-t border-gray-200">{{ $item->item->name }}</td>
                        <td class="px-4 py-2 border-t border-gray-200">{{ $item->quantity }}</td>
                        <td class="px-4 py-2 border-t border-gray-200">
                            <a href="{{ route('user.inventory.edit', ['userId' => $user->id, 'id' => $item->id]) }}" class="btn btn-primary">Edit</a>
                            <form action="#" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                            <form action="{{ route('user.inventory.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this Digital Monster?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </x-container>
</x-app-layout>
