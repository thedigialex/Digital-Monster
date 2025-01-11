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
                    <th class="w-1/6 px-4 py-2 text-left text-text"></th>
                    <th class="w-1/3 px-4 py-2 text-left text-text">Details</th>
                    <th class="w-1/5 px-4 py-2 text-left text-text">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                    <td class="px-4 py-2 text-text">
                        <span class="font-bold ml-4">Name:</span> {{ $user->name }}
                    </td>
                    <td class="px-4 py-2 text-text">
                        <span class="font-bold">Email:</span> {{ $user->email }}
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