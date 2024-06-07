<x-app-layout>
    <x-slot name="header">
        <x-sub-header>
            {{ __('Users') }}
        </x-sub-header>
    </x-slot>
    <x-body-container>
        @if ($users->isEmpty())
        <x-paragraph>No Users available.</x-paragraph>
        @else
        <x-table>
            <tr class="bg-gray-50">
                <td class="py-2 px-4 border-b w-[10%] text-lg font-bold"><x-paragraph></x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Name</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Email</x-paragraph></td>
                <td class="py-2 px-4 border-b w-[31%] text-lg font-bold"><x-paragraph>Actions</x-paragraph></td>
            </tr>
            <tbody>
                @foreach ($users as $user)
                <tr class="border-t">
                    <td class="py-2 px-4 border-b w-[10%]"><x-paragraph></x-paragraph></td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-paragraph>Available: {{ $user->name }}</x-paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <x-paragraph>Available: {{ $user->email }}</x-paragraph>
                    </td>
                    <td class="py-2 px-4 border-b w-[31%]">
                        <a href="{{ route('user.show', $user->id) }}">
                            <x-secondary-button>View</x-secondary-button>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-table>
        @endif
    </x-body-container>
</x-app-layout>