<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Dashboard') }}
        </x-fonts.sub-header>
    </x-slot>

    <x-elements.container>
        <x-dashboard.user-monster :backgroundImage="$backgroundImage" :caseImage="$caseImage" :spriteSheet="$spriteSheet"></x-dashboard.user-monster>
    </x-elements.container>

</x-app-layout>