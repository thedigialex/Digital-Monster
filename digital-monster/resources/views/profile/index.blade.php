<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Users') }}
        </x-fonts.sub-header>
    </x-slot>

    @if (session('success'))
    <x-alerts.success>{{ session('success') }}</x-alerts.success>
    @endif

    <x-container class="p-4">
        <x-accordion title="Users" :open="true" icon="fa-user">
            @if ($users->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/3 text-left">Email</x-table.header>
                        <x-table.header class="w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $user->name }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $user->email }}
                            </x-fonts.paragraph>
                        </x-table.data>
                        <x-table.data class="w-1/3 text-end">
                            <a href="{{ route('user.profile', ['id' => $user->id]) }}">
                                <x-primary-button>
                                    View Account <i class="fa fa-eye ml-2"></i>
                                </x-primary-button>
                            </a>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Users.</x-fonts.paragraph>
            @endif
        </x-accordion>
    </x-container>
</x-app-layout>