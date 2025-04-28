<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ $user->name }}
            <span class="ml-2">
                <i class="fa fa-envelope" id="copyEmailIcon" style="cursor: pointer;" onclick="copyToClipboard('{{ $user->email }}')"></i>
            </span>
        </x-fonts.sub-header>
        <a href="{{ route('users.index') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-1 lg:p-4">
        <x-slot name="header">
            <x-fonts.sub-header>Account Details</x-fonts.sub-header>
        </x-slot>
        <div class="flex flex-col md:flex-row pb-4">
            <div class="flex-1 bg-secondary p-4 rounded-md">
                <x-fonts.accent-header><strong>Name:</strong> {{ $user->name }}</x-fonts.accent-header>
                <hr class="my-2 border-accent">
                <x-fonts.paragraph><strong>Tamer Level:</strong> {{ $user->level }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Tamer Exp:</strong> {{ $user->exp }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Bits:</strong> {{ $user->bits }}</x-fonts.paragraph>
                <x-fonts.paragraph><strong>Extracted Count:</strong> {{ $user->extracted_count }}</x-fonts.paragraph>
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
        

        <x-accordion title="User Monsters" :open="true" :icon="'fa-dragon'">
            @if (!$user->userMonsters->isEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left hidden md:table-cell">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->userMonsters as $userMonster)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            @if (in_array($userMonster->monster->stage, ['Egg', 'Fresh', 'Child']) || $userMonster->type == 'Data')
                            <div class="w-16 h-16 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $userMonster->monster->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @elseif ($userMonster->type == 'Vaccine')
                            <div class="w-16 h-16 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $userMonster->monster->image_1) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @elseif ($userMonster->type == 'Virus')
                            <div class="w-16 h-16 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $userMonster->monster->image_2) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                            @endif
                        </x-table.data>
                        <x-table.data class="w-1/3 hidden md:table-cell">
                            <x-fonts.paragraph class="font-bold {{ $userMonster->main == 1 ? 'text-accent' : 'text-error' }}">
                                {{ $userMonster->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="user_monster" :id="$userMonster->id" route="user.monster.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Monsters</x-fonts.paragraph>
            @endif
            <div class="flex justify-center py-4 mt-4">
                <x-buttons.clear model="user_monster" route="user.monster.edit" icon="fa-plus" label="New" />
            </div>
        </x-accordion>

        <x-accordion title="Inventory" :open="false" :icon="'fa-boxes-stacked'">
            @if (!$user->userItems->isEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left hidden md:table-cell">Details</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->userItems as $userItem)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="w-16 h-16 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $userItem->item->image) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                        </x-table.data>
                        <x-table.data class="w-1/3 hidden md:table-cell">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $userItem->item->name }}<br>Amount: {{ $userItem->quantity }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="user_item" :id="$userItem->id" route="user.item.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Items</x-fonts.paragraph>
            @endif
            <div class="flex justify-center py-4 mt-4">
                <x-buttons.clear model="user_item" route="user.item.edit" icon="fa-plus" label="New" />
            </div>
        </x-accordion>

        <x-accordion title="Equipment" :open="false" :icon="'fa-toolbox'">
            @if (!$user->userEquipment->isEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left hidden md:table-cell">Type</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->userEquipment as $userEquipment)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="w-16 h-16 flex items-center justify-center">
                                @if ($userEquipment->equipment->type == 'Stat')
                                <img src="{{ asset('storage/' . $userEquipment->equipment->image) }}" alt="Equipment Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                                @else
                                <i class="fa {{ $userEquipment->equipment->icon }} text-accent text-5xl"></i>
                                @endif
                            </div>
                        </x-table.data>
                        <x-table.data class="w-1/3 hidden md:table-cell">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $userEquipment->equipment->type }} {{ $userEquipment->equipment->stat }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="user_equipment" :id="$userEquipment->id" route="user.equipment.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Equipment</x-fonts.paragraph>
            @endif
            <div class="flex justify-center py-4 mt-4">
                <x-buttons.clear model="user_equipment" route="user.equipment.edit" icon="fa-plus" label="New" />
            </div>
        </x-accordion>

        <x-accordion title="Locations" :open="false" :icon="'fa-location-crosshairs'">
            @if (!$user->userLocations->isEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Image</x-table.header>
                        <x-table.header class="w-1/3 text-left hidden md:table-cell">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user->userLocations as $userLocation)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="w-16 h-16 flex items-center justify-center">
                                <img src="{{ asset('storage/' . $userLocation->location->image) }}" alt="Location Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            </div>
                        </x-table.data>
                        <x-table.data class="w-1/3 hidden md:table-cell">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $userLocation->location->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end">
                                <x-buttons.session model="user_location" :id="$userLocation->id" route="user.location.edit" />
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Locations</x-fonts.paragraph>
            @endif
            <div class="flex justify-center py-4 mt-4">
                <x-buttons.clear model="location_equipment" route="user.location.edit" icon="fa-plus" label="New" />
            </div>
        </x-accordion>
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