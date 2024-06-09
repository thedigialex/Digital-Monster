<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ __('User Details') }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        <x-sub-header>
            {{ $user->name }}'s Profile
        </x-sub-header>
        <div>
            <x-paragraph class="font-bold">Email: {{ $user->email }}</x-paragraph>
            <x-paragraph class="font-bold">Nickname: {{ $user->nickname }}</x-paragraph>
        </div>
        <x-table-header>
            <div class="flex items-center space-x-4">
                <x-paragraph class="font-bold">User Digital Monsters</x-paragraph>
            </div>
            <a href="{{ route('user.handleMonster', [$user->id]) }}">
                <x-primary-button>New Digital Monster</x-primary-button>
            </a>
        </x-table-header>
        @if($user->userDigitalMonsters->isEmpty())
        <x-paragraph class="font-bold">No digital monsters available.</x-paragraph>
        @else
        <x-table>
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border-b w-[10%] text-lg font-bold"><x-paragraph></x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Name</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Details</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Actions</x-paragraph></td>
            </tr>
            <tbody>
                @foreach ($user->userDigitalMonsters as $monster)
                <tr class="border-t {{ $monster->isMain ? 'bg-green-100' : '' }}">
                    <td class="py-2 px-4 border-b w-[10%]"><x-paragraph></x-paragraph></td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-paragraph>{{ $monster->name }}</x-paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-paragraph>Egg ID: {{ $monster->digitalMonster->egg_id }}</x-paragraph>
                        <x-paragraph>Monster ID: {{ $monster->digitalMonster->monster_id }}</x-paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <a href="{{ route('user.handleMonster', [$user->id, $monster->id]) }}">
                            <x-secondary-button>Edit</x-secondary-button>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-table>
        @endif
        <x-table-header>
            <div class="flex items-center space-x-4">
                <x-paragraph class="font-bold">User Items</x-paragraph>
            </div>
            <a href="{{ route('user.handleItem', [$user->id]) }}">
                <x-primary-button>New Item</x-primary-button>
            </a>
        </x-table-header>
        @if($user->inventory->isEmpty())
        <x-paragraph class="font-bold">No Items.</x-paragraph>
        @else
        <x-table>
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border-b w-[10%] text-lg font-bold"><x-paragraph></x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Name</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Details</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Actions</x-paragraph></td>
            </tr>
            <tbody>
                @foreach ($user->inventory as $item)
                <tr class="border-t">
                    <td class="py-2 px-4 border-b w-[10%]"><x-paragraph></x-paragraph></td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-paragraph>{{ $item->item->name }}</x-paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-paragraph>Quantity: {{ $item->quantity }}</x-paragraph>
                        <x-paragraph>Type: {{ $item->item->type }}</x-paragraph>
                        <x-paragraph>Equipped: {{ $item->is_equipped }}</x-paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <a href="{{ route('user.handleItem', [$user->id, $item->id]) }}">
                            <x-secondary-button>Edit</x-secondary-button>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-table>
        @endif
    </x-body-container>
</x-app-layout>