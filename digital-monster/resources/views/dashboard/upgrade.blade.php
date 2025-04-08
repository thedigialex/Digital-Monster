<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Upgrade Station
        </x-fonts.sub-header>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                Upgrade Station
            </x-fonts.sub-header>
        </x-slot>
        <x-container.background :background="$background">
            <x-alerts.spinner id="loading-section" />
            @if ($userEquipment->isEmpty())
            <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">No Equipment to upgrade</x-fonts.paragraph>
            @else
            <div id="equipment-section" class="w-full md:w-3/4 bg-primary rounded-md overflow-auto my-4 p-4">
                <x-fonts.sub-header class="border-b-2 border-accent mb-4">Upgradable Equipment</x-fonts.sub-header>
                <div class="flex flex-wrap justify-center gap-4 my-4">
                    @foreach ($userEquipment as $equipment)
                    <div class="flex flex-col items-center w-40 p-2 bg-secondary border-2 border-accent rounded-md">
                        <i class="fas {{ $equipment->equipment->icon ?? 'fa-question-circle' }} text-4xl text-text relative w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center"></i>
                        <x-fonts.paragraph>{{ $equipment->equipment->stat ?? $equipment->equipment->type }}</x-fonts.paragraph>
                        <x-fonts.paragraph class="text-accent">Lvl {{ $equipment->level ?? 1 }}</x-fonts.paragraph>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </x-container.background>
    </x-container>
</x-app-layout>