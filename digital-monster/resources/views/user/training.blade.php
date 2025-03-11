<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ $user->name }}
            </x-fonts.sub-header>
        </div>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header>
                    Training
                </x-fonts.sub-header>
                <x-fonts.paragraph>
                    Training: {{ $userMonster->name }} using {{ $userEquipment->equipment->name }}
                </x-fonts.paragraph>
            </div>
        </x-slot>
        <div class="p-4"
            style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
            <div class="flex justify-center items-center space-x-4 py-6">
                <div class="relative w-16 h-16 p-2">
                    <div data-equipment='@json($userEquipment)' id="equipment-sprite" class="w-full h-full bg-contain bg-no-repeat"></div>
                </div>
                <div class="relative w-16 h-16 p-2">
                    <div data-monster='@json($userMonster)' id="monster-sprite" class="w-full h-full bg-contain bg-no-repeat"></div>
                </div>
            </div>

            <div class="progress-container w-full h-8 bg-secondary mt-6 relative overflow-hidden rounded-lg">
                <div id="progress-bar" class="h-full bg-accent w-0"></div>
            </div>

            <div class="flex justify-center mt-4">
                <button id="startTraining" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Start Training</button>
                <button id="stopTraining" class="px-4 py-2 bg-red-500 text-white rounded-lg ml-2" disabled>Stop</button>
            </div>
        </div>


        <form id="trainingForm" action="{{ route('train.update') }}" method="POST">
            @csrf
            <input type="hidden" name="percentage" id="percentage" value="0">
            <input type="hidden" name="equipment_id" value="{{ $userEquipment->id }}">
        </form>
    </x-container>

    <script>
        let progress = 0;
        let direction = 1;
        let interval;

        // Start Training
        document.getElementById('startTraining').addEventListener('click', function() {
            this.disabled = true;
            document.getElementById('stopTraining').disabled = false;
            progress = 0;
            direction = 1;

            interval = setInterval(() => {
                progress += 5 * direction;
                if (progress >= 100 || progress <= 0) {
                    direction *= -1;
                }
                document.getElementById('progress-bar').style.width = progress + "%";
            }, 100);
        });

        // Stop Training
        document.getElementById('stopTraining').addEventListener('click', function() {
            clearInterval(interval);
            document.getElementById('percentage').value = progress;
            document.getElementById('trainingForm').submit();
        });

        // Monster Sprite Animation
        const monsterSprite = document.getElementById('monster-sprite');
        const userMonster = JSON.parse(monsterSprite.getAttribute('data-monster'));
        let monsterFrame = 0;
        monsterSprite.style.backgroundImage = `url(/storage/${userMonster.monster.image_0})`;
        if (userMonster.monster.stage !== 'Fresh' && userMonster.monster.stage !== 'Child') {
            switch (userMonster.monster.type) {
                case "Vaccine":
                    monsterSprite.style.backgroundImage = `url(/storage/${userMonster.monster.image_1})`;
                    break;
                case "Virus":
                    monsterSprite.style.backgroundImage = `url(/storage/${userMonster.monster.image_2})`;
                    break;
            }
        }
        setInterval(() => {
            monsterFrame = (monsterFrame + 1) % 3;
            monsterSprite.style.backgroundPositionX = `-${monsterFrame * 48}px`;
        }, 300);

        // Equipment Sprite Animation
        const equipmentSprite = document.getElementById('equipment-sprite');
        const userEquipment = JSON.parse(equipmentSprite.getAttribute('data-equipment'));
        let equipmentFrame = 0;
        equipmentSprite.style.backgroundImage = `url(/storage/${userEquipment.equipment.image})`;
        setInterval(() => {
            equipmentFrame = (equipmentFrame + 1) % 3;
            equipmentSprite.style.backgroundPositionX = `-${equipmentFrame * 48}px`;
        }, 400);
    </script>
</x-app-layout>