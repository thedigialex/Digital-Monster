<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Users') }}
            </x-fonts.sub-header>
        </div>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container>
        <div class="flex justify-between items-center mb-2">
            <x-fonts.sub-header>
                Users
            </x-fonts.sub-header>
        </div>
        <table class="min-w-full border border-primary border-4">
            <thead class="bg-primary">
                <tr>
                    <th class="w-1/6 px-4 py-2 text-left text-text">Name</th>
                    <th class="w-1/3 px-4 py-2 text-left text-text">Email</th>
                    <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                    <td class="px-4 py-2 text-text">
                        {{ $user->name }}
                    </td>
                    <td class="px-4 py-2 text-text">
                        {{ $user->email }}
                    </td>
                    <td class="px-4 py-2 text-end space-x-4">
                        <a href="{{ route('user.profile', ['id' => $user->id]) }}">
                            <x-primary-button>
                                View Account <i class="fa fa-eye ml-2"></i>
                            </x-primary-button>
                        </a>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </x-container>
</x-app-layout>