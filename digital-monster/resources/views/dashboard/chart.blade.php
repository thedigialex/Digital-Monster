<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Evolution Chart
        </x-fonts.sub-header>
        <a href="{{ route('digigarden') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>
    <x-container class="p-2 md:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Evolution Chart</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                This site lets you collect, evolve, and manage digital creatures. Start by registering an account to receive dataCrystals, which you can use to choose an egg from the DigiConverge page. Hatch and evolve your creatures in the DigiGarden, track their progress, and build your ultimate digital team!
            </x-fonts.paragraph>
        </x-slot>
    </x-container>
</x-app-layout>