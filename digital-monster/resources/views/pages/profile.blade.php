<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Profile: {{ $user->name }}
            <span class="ml-2">
                <i class="fa fa-envelope" id="copyEmailIcon" style="cursor: pointer;" onclick="copyToClipboard('{{ $user->email }}')"></i>
            </span>
        </x-fonts.sub-header>
        <a href="{{ route('users.index') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif


    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Account Details</x-fonts.sub-header>
        </x-slot>
        <div class="p-4 flex flex-col md:flex-row">
            <div class="flex-1 bg-secondary p-4 rounded-md">
                <x-fonts.accent-header><strong>Name:</strong> {{ $user->name }}</x-fonts.accent-header>
                <hr class="my-2 border-accent">
                <x-fonts.paragraph><strong>Tamer Level:</strong> {{ $user->tamer_level }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Tamer Exp:</strong> {{ $user->tamer_exp }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Bits:</strong> {{ $user->bits }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Max Monster Amount:</strong> {{ $user->max_monster_amount }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Score:</strong> {{ $user->score }}</x-fonts.paragraph>
            </div>
            <div class="w-full md:w-1/4 bg-neutral p-4 rounded-md md:ml-4">
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
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header class="text-accent">
                    User Monsters
                </x-fonts.sub-header>
                <x-buttons.clear model="user_monster" route="user.monster.edit" icon="fa-plus" label="Add New" />
            </div>
        </x-slot>
        <div class="p-4">
            @if (!$user->userMonsters->isEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->userMonsters as $userMonster)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/3">
                            @if (in_array($userMonster->monster->stage, ['Egg', 'Fresh', 'Child']) || $userMonster->type == 'Data')
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $userMonster->monster->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @elseif ($userMonster->type == 'Virus')
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $userMonster->monster->image_1) }}" alt="Monster Image" class="w-full h-full object-cover"style="object-position: 0 0;">
                            </div>
                            @elseif ($userMonster->type == 'Vaccine')
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $userMonster->monster->image_2) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <x-fonts.paragraph class="font-bold {{ $userMonster->main == 1 ? 'text-accent' : 'text-error' }}">
                                {{ $userMonster->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3 text-end">
                            <x-buttons.session model="user_monster" :id="$userMonster->id" route="user.monster.edit" />
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Monsters</x-fonts.paragraph>
            @endif
        </div>
    </x-container>

    <x-container>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header class="text-accent">
                    Inventory
                </x-fonts.sub-header>
                <x-buttons.clear model="user_item" route="user.item.edit" icon="fa-plus" label="Add New" />
            </div>
        </x-slot>
        <div class="p-4">
            @if (!$user->userItems->isEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left">Details</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->userItems as $userItem)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/3">
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $userItem->item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <x-fonts.paragraph class="font-bold {{ $userItem->equipped == 1 ? 'text-accent' : 'text-text' }}">
                                {{ $userItem->item->name }}<br>Amount: {{ $userItem->quantity }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3 text-end">
                            <x-buttons.session model="user_item" :id="$userItem->id" route="user.item.edit" />
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Items</x-fonts.paragraph>
            @endif
        </div>
    </x-container>

    <x-container>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header class="text-accent">
                    Equipment
                </x-fonts.sub-header>
                <x-buttons.clear model="user_equipment" route="user.equipment.edit" icon="fa-plus" label="Add New" />
            </div>
        </x-slot>
        <div class="p-4">
            @if (!$user->userEquipment->isEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->userEquipment as $userEquipment)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/3">
                            <div class="w-16 h-16 overflow-hidden">
                                <img src="{{ asset('storage/' . $userEquipment->equipment->image) }}" alt="Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $userEquipment->equipment->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3 text-end">
                            <x-buttons.session model="user_equipment" :id="$userEquipment->id" route="user.equipment.edit" />
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Equipment</x-fonts.paragraph>
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