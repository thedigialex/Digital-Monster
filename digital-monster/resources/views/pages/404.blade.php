<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            404
        </x-fonts.sub-header>
    </x-slot>
    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Page Not Found</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Oops! The page you are looking for doesn't exist.
            </x-fonts.paragraph>
        </x-slot>
    </x-container>
</x-app-layout>