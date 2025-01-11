<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('User') }}: {{ $user->name }}
                <span class="ml-2">
                    <i class="fa fa-envelope" id="copyEmailIcon" style="cursor: pointer;" onclick="copyToClipboard('{{ $user->email }}')"></i>
                </span>
            </x-fonts.sub-header>
            <a href="{{ route('users.index') }}">
                <x-primary-button>
                    Go Back <i class="fa fa-arrow-left ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    @if (session('success'))
    <x-alert-success>{{ session('success') }}</x-alert-success>
    @endif

    <x-container>
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                User Details
            </x-fonts.sub-header>
        </div>

        <details class="mt-4">
            <summary class="cursor-pointer font-semibold text-lg">Click to view details</summary>
            <div class="mt-2 space-y-4">
                <div><strong>Name:</strong> {{ $user->name }}</div>
                <div><strong>Email:</strong> {{ $user->email }}</div>
                <div><strong>Role:</strong> {{ ucfirst($user->role) }}</div>
                <div><strong>Tamer Level:</strong> {{ $user->tamer_level }}</div>
                <div><strong>Tamer Exp:</strong> {{ $user->tamer_exp }}</div>
                <div><strong>Bits:</strong> {{ $user->bits }}</div>
                <div><!--<strong>Max Monster Amount:</strong> {{ $user->max_monster_amount }}</div>-->
                    <div><strong>Score:</strong> {{ $user->score }}</div>
                    <div><strong>Account Created At:</strong> {{ $user->created_at }}</div>
                    <div><strong>Last Updated At:</strong> {{ $user->updated_at }}</div>
                </div>
        </details>
    </x-container>

    <x-container>
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                Training Equipment
            </x-fonts.sub-header>
            <a href="{{ route('user.training_equipment.edit', ['userId' => $user->id]) }}">
                <x-primary-button>
                    Add new<i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
        @if ($user->trainingEquipments->isEmpty())
        <p>No training equipment found for this user.</p>
        @else
        <table class="min-w-full border-collapse border border-gray-200 mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600">Equipment Name</th>
                    <th class="px-4 py-2 text-left text-gray-600">Level</th>
                    <th class="px-4 py-2 text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->trainingEquipments as $equipment)
                <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                    <td class="px-4 py-2 border-t border-gray-200">{{ $equipment->trainingEquipment->name }}</td>
                    <td class="px-4 py-2 border-t border-gray-200">{{ $equipment->level }}</td>
                    <td class="px-4 py-2 border-t border-gray-200">
                        <a href="{{ route('user.training_equipment.edit', ['userId' => $user->id, 'id' => $equipment->id]) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('user.training_equipment.destroy', $equipment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this training equipment?');">
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
    </x-container>

    <x-container>
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                User Digital Monster
            </x-fonts.sub-header>
            <a href="{{ route('user.digital_monsters.edit', ['userId' => $user->id]) }}">
                <x-primary-button>
                    Add new<i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
        @if ($user->digitalMonsters->isEmpty())
        <p>No digital monsters found for this user.</p>
        @else
        <table class="min-w-full border-collapse border border-gray-200 mt-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left text-gray-600">Name</th>
                    <th class="px-4 py-2 text-left text-gray-600">Type</th>
                    <th class="px-4 py-2 text-left text-gray-600">Level</th>
                    <th class="px-4 py-2 text-left text-gray-600">Strength</th>
                    <th class="px-4 py-2 text-left text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->digitalMonsters as $digitalMonster)
                <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                    <td class="px-4 py-2 border-t border-gray-200">{{ $digitalMonster->name }}</td>
                    <td class="px-4 py-2 border-t border-gray-200">{{ $digitalMonster->type }}</td>
                    <td class="px-4 py-2 border-t border-gray-200">{{ $digitalMonster->level }}</td>
                    <td class="px-4 py-2 border-t border-gray-200">{{ $digitalMonster->strength }}</td>
                    <td class="px-4 py-2 border-t border-gray-200">
                        <a href="{{ route('user.digital_monsters.edit', ['userId' => $user->id, 'id' => $digitalMonster->id]) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('user.digital_monsters.destroy', $digitalMonster->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this Digital Monster?');">
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
    </x-container>
    <x-container>
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                Inventory
            </x-fonts.sub-header>
            <a href="{{ route('user.inventory.edit', ['userId' => $user->id]) }}">
                <x-primary-button>
                    Add new<i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
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
    </x-container>
</x-app-layout>

<script>
    function copyToClipboard(email) {
        const tempInput = document.createElement('input');
        tempInput.value = email;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
    }
</script>