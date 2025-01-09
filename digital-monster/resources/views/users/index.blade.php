<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Users') }}
            </x-fonts.sub-header>
        </div>
    </x-slot>

    @if (session('success'))
    <x-alert-success>{{ session('success') }}</x-alert-success>
    @endif

    <x-container>
        <table class="min-w-full border border-primary border-4">
            <thead class="bg-primary">
                <tr>
                    <th class="w-1/3 px-4 py-2 text-left text-text">Name</th>
                    <th class="w-1/3 px-4 py-2 text-left text-text">Email</th>
                    <th class="w-1/5 px-4 py-2 text-center text-text">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                    <td class="px-4 py-2 text-text">{{ $user->name }}</td>
                    <td class="px-4 py-2 text-text">{{ $user->email }}</td>
                    <td class="px-4 py-2 flex justify-center items-center space-x-2">
                            <a href="{{ route('user.digital_monsters', ['id' => $user->id]) }}">
                                <x-primary-button>View Monsters</x-primary-button>
                            </a>
                            <a href="{{ route('user.inventory', ['id' => $user->id]) }}">
                                <x-primary-button>View Inventory</x-primary-button>
                            </a>
                            <a href="{{ route('user.training_equipment', ['id' => $user->id]) }}" >
                                <x-primary-button>View Equipment</x-primary-button>
                            </a>
                        </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-container>
</x-app-layout>