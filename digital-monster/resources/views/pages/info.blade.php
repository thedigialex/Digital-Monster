<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Information
        </x-fonts.sub-header>
    </x-slot>
    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Information</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
               stuff
            </x-fonts.paragraph>
        </x-slot>
    </x-container>
</x-app-layout>