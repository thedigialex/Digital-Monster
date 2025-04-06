<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Privacy
        </x-fonts.sub-header>
        <a href="{{ route('profile.edit') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>
    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Privacy</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Privacy stuff
            </x-fonts.paragraph>
        </x-slot>
    </x-container>
</x-app-layout>