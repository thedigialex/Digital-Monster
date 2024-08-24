<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('User Details') }}
        </x-fonts.sub-header>
    </x-slot>
    <x-elements.container>
        <x-fonts.sub-header>
            {{ $user->name }}'s Profile
        </x-fonts.sub-header>
       
        <x-tables.table-header>
            <div class="flex items-center space-x-4">
                <x-fonts.paragraph class="font-bold">User Digital Monsters</x-fonts.paragraph>
            </div>
            <a href="{{ route('user.handleMonster', [$user->id]) }}">
                <x-elements.primary-button>New Digital Monster</x-elements.primary-button>
            </a>
        </x-tables.table-header>
        @if($user->userDigitalMonsters->isEmpty())
        <x-fonts.paragraph class="font-bold">No digital monsters available.</x-fonts.paragraph>
        @else
        <x-tables.table>
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border-b w-[10%] text-lg font-bold"><x-fonts.paragraph></x-fonts.paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-fonts.paragraph>Name</x-fonts.paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-fonts.paragraph>Details</x-fonts.paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-fonts.paragraph>Actions</x-fonts.paragraph></td>
            </tr>
            <tbody>
                @foreach ($user->userDigitalMonsters as $monster)
                <tr class="border-t {{ $monster->isMain ? 'bg-green-100' : '' }}">
                    <td class="py-2 px-4 border-b w-[10%]"><x-fonts.paragraph></x-fonts.paragraph></td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-fonts.paragraph>{{ $monster->name }}</x-fonts.paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-fonts.paragraph>Egg: {{ $monster->digitalMonster->eggGroup->name }}</x-fonts.paragraph>
                        <x-fonts.paragraph>Monster ID: {{ $monster->digitalMonster->monsterId }}</x-fonts.paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <a href="{{ route('user.handleMonster', [$user->id, $monster->id]) }}">
                            <x-elements.secondary-button>Edit</x-elements.secondary-button>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-tables.table>
        @endif
        <x-tables.items-table :itemTypes="$itemTypes" :items="$items" :route="route('user.handleItem', [$user->id])" :user="$user"/>

    </x-elements.container>
</x-app-layout>