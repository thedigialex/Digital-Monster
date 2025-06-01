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
        <x-container.background :background="$background" class="rounded-b-md">
            <x-alerts.spinner id="loading-section" />
            @if ($userEquipments->isEmpty())
            <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">No Equipment to upgrade</x-fonts.paragraph>
            @else
            <div id="equipment-section" class="w-full md:w-3/4 bg-primary rounded-md overflow-auto p-4">
                <x-fonts.sub-header class="border-b-2 border-accent mb-4">Upgradable Equipment</x-fonts.sub-header>
                <div class="flex flex-wrap justify-center gap-4 my-8">
                    @foreach ($userEquipments as $userEquipment)
                    <div class="upgradeEquipment flex flex-col items-center w-36 p-2 bg-secondary border-2 border-accent rounded-md cursor-pointer" data-equipment='{{ json_encode($userEquipment) }}'>
                        <div class="relative w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center">
                            <i class="fas {{ $userEquipment->equipment->icon }} text-4xl text-text"></i>
                            <span class="absolute bottom-1 right-1 bg-accent text-text text-xs px-2 py-1 rounded-md">
                                Lvl {{ $userEquipment->level }}
                            </span>
                        </div>
                        <x-fonts.paragraph>{{ $userEquipment->equipment->stat ?? $userEquipment->equipment->type }}</x-fonts.paragraph>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </x-container.background>
    </x-container>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loadingSection = document.getElementById('loading-section');
        const equipmentSection = document.getElementById('equipment-section');
        document.querySelectorAll('.upgradeEquipment').forEach(div => {
            div.addEventListener('click', function() {
                loadingSection.classList.remove("hidden");
                equipmentSection.classList.add("hidden");
                let equipment = JSON.parse(this.getAttribute('data-equipment'));
                const equipmentCard = this;
                fetch("{{ route('upgradeStation.upgrade') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            equipment_id: equipment.id
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        equipmentSection.classList.remove("hidden");
                        loadingSection.classList.add("hidden");
                        if (result.successful) {
                            //remove clicked div
                            equipmentCard.remove();
                        }
                    });
            });
        });
    });
</script>