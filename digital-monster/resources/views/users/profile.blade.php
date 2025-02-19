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
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container>
        <x-slot name="header">
            <x-fonts.accent-header class="text-primary pb-2"><strong>User Details</x-fonts.accent-header>
        </x-slot>
        <div class="p-4 flex flex-col md:flex-row bg-secondary rounded-b-lg">
            <div class="flex-1 bg-neutral p-4 rounded-md">
                <x-fonts.accent-header><strong>Name:</strong> {{ $user->name }}</x-fonts.accent-header>
                <hr class="my-2 border-accent">
                <x-fonts.paragraph><strong>Tamer Level:</strong> {{ $user->tamer_level }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Tamer Exp:</strong> {{ $user->tamer_exp }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Bits:</strong> {{ $user->bits }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Max Monster Amount:</strong> {{ $user->max_monster_amount }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Score:</strong> {{ $user->score }}</x-fonts.paragraph>
            </div>
            <div class="w-full md:w-1/4 bg-primary p-4 rounded-md md:ml-4">
                <x-fonts.paragraph><strong>Role:</strong> {{ ucfirst($user->role) }}</x-fonts.paragraph>
                <hr class="my-2">
                <x-fonts.paragraph><strong>Email:</strong> {{ $user->email }} </x-fonts.paragraph>
                <hr class="my-2">
                <x-fonts.paragraph><strong>Account Created At:</strong> {{ $user->created_at->format('m/d/Y') }} </x-fonts.paragraph>
                <x-fonts.paragraph><strong>Last Updated At:</strong> {{ $user->updated_at->format('m/d/Y') }} </x-fonts.paragraph>
            </div>
        </div>
    </x-container>

    <x-container>
        <div class="flex justify-between items-center p-4 bg-secondary rounded-t-lg">
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
        <x-fonts.paragraph>No digital monsters found for this user</x-fonts.paragraph>
        @else
        <table class="min-w-full border border-primary border-4">
            <thead class="bg-primary">
                <tr>
                    <th class="w-1/6 px-4 py-2 text-left text-text">Image</th>
                    <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                    <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->digitalMonsters as $userDigitalMonster)
                <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                    <td class="px-4 py-2">
                        @if (isset($userDigitalMonster->digitalMonster->sprite_image_0))
                        <div class="w-16 h-16 overflow-hidden">
                            <img src="{{ asset('storage/' . $userDigitalMonster->digitalMonster->sprite_image_0) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-text">
                        <span class="font-bold">Main:</span> {{ $userDigitalMonster->isMain }}
                        <span class="font-bold ml-4">Name:</span> {{ $userDigitalMonster->name }}
                        <span class="font-bold ml-4">Type:</span> {{ $userDigitalMonster->type }}
                    </td>
                    <td class="px-4 py-2 text-end space-x-4">
                        <a href="{{ route('user.digital_monsters.edit', ['userId' => $user->id, 'id' => $userDigitalMonster->id]) }}">
                            <x-primary-button>
                                Edit <i class="fa fa-edit ml-2"></i>
                            </x-primary-button>
                        </a>
                        <form action="{{ route('user.digital_monsters.destroy', $userDigitalMonster->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this User Digital Monster?');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button type="submit">Delete <i class="fa fa-trash ml-2"></i> </x-danger-button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </x-container>

    <x-container>
        <div class="flex justify-between items-center p-4 bg-secondary rounded-t-lg">
            <x-fonts.sub-header>
                Inventory
            </x-fonts.sub-header>
            <a href="{{ route('user.inventory.edit', ['userId' => $user->id]) }}">
                <x-primary-button>
                    Add new<i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
        <div class=" bg-secondary rounded-b-lg">
            @if ($user->inventories->isEmpty())
            <x-fonts.paragraph class="text-text p-4">No items.</x-fonts.paragraph>
            @else
            <table class="min-w-full border border-primary border-4">
                <thead class="bg-primary">
                    <tr>
                        <th class="w-1/6 px-4 py-2 text-left text-text">Image</th>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                        <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->inventories as $inventoryItem)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <td class="px-4 py-2">
                            @if (isset($inventoryItem->item->image))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $inventoryItem->item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-text">
                            <span class="font-bold">Equipped:</span> {{ $inventoryItem->isEquipped }}
                            <span class="font-bold ml-4">Name:</span> {{ $inventoryItem->item->name }}
                            <span class="font-bold ml-4">Type:</span> {{ $inventoryItem->item->type }}
                            <span class="font-bold ml-4">Quantity:</span> {{ $inventoryItem->quantity }}
                        </td>
                        <td class="px-4 py-2 text-end space-x-4">
                            <a href="{{ route('user.inventory.edit', ['userId' => $user->id, 'id' => $inventoryItem->id]) }}">
                                <x-primary-button>
                                    Edit <i class="fa fa-edit ml-2"></i>
                                </x-primary-button>
                            </a>
                            <form action="{{ route('user.inventory.destroy', $inventoryItem->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Delete <i class="fa fa-trash ml-2"></i> </x-danger-button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>

    </x-container>

    <x-container>
        <div class="flex justify-between items-center p-4 bg-secondary rounded-t-lg">
            <x-fonts.sub-header>
                Training Equipment
            </x-fonts.sub-header>
            <a href="{{ route('user.training_equipment.edit', ['userId' => $user->id]) }}">
                <x-primary-button>
                    Add new<i class="fa fa-plus ml-2"></i>
                </x-primary-button>
            </a>
        </div>
        <div class=" bg-secondary rounded-b-lg">
            @if ($user->trainingEquipments->isEmpty())
            <x-fonts.paragraph class="text-text p-4">No training equipment.</x-fonts.paragraph>
            @else
            <table class="min-w-full border border-primary border-4 bg-secondary">
                <thead class="bg-primary">
                    <tr>
                        <th class="w-1/6 px-4 py-2 text-left text-text">Image</th>
                        <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                        <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->trainingEquipments as $usertrainingEquipment)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <td class="px-4 py-2">
                            @if (isset($usertrainingEquipment->trainingEquipment->image))
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $usertrainingEquipment->trainingEquipment->image) }}" alt="Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-text">
                            <span class="font-bold">Name:</span> {{ $usertrainingEquipment->trainingEquipment->name }}
                            <span class="font-bold ml-4">Level:</span> {{ $usertrainingEquipment->level }}
                        </td>
                        <td class="px-4 py-2 text-end space-x-4">
                            <a href="{{ route('user.training_equipment.edit', ['userId' => $user->id, 'id' => $usertrainingEquipment->id]) }}">
                                <x-primary-button>
                                    Edit <i class="fa fa-edit ml-2"></i>
                                </x-primary-button>
                            </a>
                            <form action="{{ route('user.training_equipment.destroy', $usertrainingEquipment->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this training equipment?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">Delete <i class="fa fa-trash ml-2"></i> </x-danger-button>
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