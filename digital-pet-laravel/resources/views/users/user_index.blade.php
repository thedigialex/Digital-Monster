<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Users') }}
        </x-fonts.sub-header>
    </x-slot>
    <x-elements.container>
        @if ($users->isEmpty())
        <x-fonts.paragraph>No Users available.</x-fonts.paragraph>
        @else
        <x-tables.table>
            <tr class="bg-secondary">
                <td class="py-2 px-4 font-bold"><x-fonts.paragraph>Name</x-fonts.paragraph></td>
                <td class="py-2 px-4 font-bold"><x-fonts.paragraph>Email</x-fonts.paragraph></td>
                <td class="py-2 px-4 font-bold"><x-fonts.paragraph>Actions</x-fonts.paragraph></td>
            </tr>
            <tbody>
                @foreach ($users as $user)
                <tr >
                    <td class="py-2 px-4 border-b">
                        <x-fonts.paragraph>{{ $user->name }}</x-fonts.paragraph>
                    </td>
                    <td class="py-2 px-4 border-b ">
                        <x-fonts.paragraph>{{ $user->email }}</x-fonts.paragraph>
                    </td>
                    <td class="py-2 px-4 border-b ">
                        <a href="{{ route('user.show', $user->id) }}">
                            <x-elements.secondary-button>View</x-elements.secondary-button>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-tables.table>
        @endif
    </x-elements.container>
</x-app-layout>