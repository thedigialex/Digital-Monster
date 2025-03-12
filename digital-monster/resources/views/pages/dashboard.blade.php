<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                {{ $user->name }}'s Farm
            </x-fonts.sub-header>
        </div>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header>
                    Farm
                </x-fonts.sub-header>
                <x-fonts.paragraph>
                    {{ $totalMonsters }} / {{ $user->max_monster_amount }}
                </x-fonts.paragraph>
                <x-buttons.clear model="user_monster_training" route="user.monster.edit" icon="fa-robot" label="Main" />
            </div>
        </x-slot>

        <div
            id="monster-container"
            class="relative w-full h-[550px] overflow-hidden rounded-b-md shadow-lg"
            data-monsters='@json($userMonsters)'
            style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
        </div>

        <div id="stats-panel" class="hidden bg-secondary w-full p-4 shadow-lg rounded-b-md">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header id="stats-title">Monster Stats</x-fonts.sub-header>
                <button id="close-stats" class="text-accent font-bold text-4xl p-2">&times;</button>
            </div>
            <div id="stats-content">
                <x-fonts.paragraph id="stat-name"><strong>Name:</strong> <span></span></x-fonts.paragraph>
                <x-fonts.paragraph id="stat-stage"><strong>Stage:</strong> <span></span></x-fonts.paragraph>
                <x-fonts.paragraph id="stat-stats" style="display: flex;">
                    <span id="stat-strength"></span> |
                    <span id="stat-agility"></span> |
                    <span id="stat-defense"></span> |
                    <span id="stat-mind"></span>
                </x-fonts.paragraph>

                <x-container.modal name="user-monster-training" title="Equipment Training" focusable>
                    <x-slot name="button">
                        @foreach ($userEquipment as $userEquipment)
                        <x-buttons.primary @click="open = true" label="{{ $userEquipment->equipment->stat }}" icon="fa-weight" />
                        @endforeach
                    </x-slot>
                    <div class="p-4"
                        style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
                        <div class="flex justify-center items-center space-x-4 py-6">
                            <div class="relative w-16 h-16 p-2">
                               <div data-equipment='@json($userMonsters[1])' id="equipment-sprite" class="w-full h-full"></div>
                            </div>
                            <div class="relative w-16 h-16 p-2">
                                <div data-monster='@json($userMonsters[0])' id="monster-sprite" class="w-full h-full"></div>
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

                    <!--
                        <form id="trainingForm" action="{{ route('train.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="percentage" id="percentage" value="0">
                            <input type="hidden" name="equipment_id" value="{{ $userEquipment->id }}">
                        </form>
                    -->
                </x-container.modal>
            </div>
        </div>
    </x-container>

    <script>
        const container = document.getElementById('monster-container');
        const statsPanel = document.getElementById('stats-panel');
        const closeStatsButton = document.getElementById('close-stats');
        const statName = document.getElementById('stat-name').querySelector('span');
        const statStage = document.getElementById('stat-stage').querySelector('span');
        const statStrength = document.getElementById('stat-strength');
        const statAgility = document.getElementById('stat-agility');
        const statDefense = document.getElementById('stat-defense');
        const statMind = document.getElementById('stat-mind');
        const userMonsters = JSON.parse(container.getAttribute('data-monsters'));
        const screenWidth = container.offsetWidth;
        const screenHeight = container.offsetHeight;

        userMonsters.forEach(userMonster => {
            const monsterDiv = document.createElement('div');
            monsterDiv.className = 'monster';
            monsterDiv.style.width = '48px';
            monsterDiv.style.height = '48px';
            monsterDiv.style.position = 'absolute';

            const spriteDiv = document.createElement('div');
            spriteDiv.className = 'sprite';
            spriteDiv.style.width = '100%';
            spriteDiv.style.height = '100%';
            spriteDiv.style.backgroundImage = `url(/storage/${userMonster.monster.image_0})`;
            spriteDiv.style.backgroundSize = '480px 48px';

            if (userMonster.monster.stage != 'Fresh' && userMonster.monster.stage != 'Child') {
                switch (userMonster.type) {
                    case "Virus":
                        spriteDiv.style.backgroundImage = `url(/storage/${userMonster.monster.image_1})`;
                        break;
                    case "Vaccine":
                        spriteDiv.style.backgroundImage = `url(/storage/${userMonster.monster.image_2})`;
                        break;
                }
            }

            const tooltip = document.createElement('span');
            tooltip.className = 'tooltip';
            tooltip.innerText = userMonster.monster.name;

            monsterDiv.appendChild(spriteDiv);
            monsterDiv.appendChild(tooltip);
            container.appendChild(monsterDiv);

            let frame = Math.floor(Math.random() * 3);
            const animationInterval = 300 + Math.random() * 300;
            setInterval(() => {
                frame = (frame + 1) % 3;
                spriteDiv.style.backgroundPositionX = `-${frame * 48}px`;
            }, animationInterval);

            let previousX = parseFloat(monsterDiv.style.left) || 0;
            const x = Math.random() * (screenWidth - 48);
            const y = Math.random() * (screenHeight - 48);
            monsterDiv.style.left = `${x}px`;
            monsterDiv.style.top = `${y}px`;

            const movementInterval = 4000 + Math.random() * 2000;
            setInterval(() => {
                const currentX = parseFloat(monsterDiv.style.left) || 0;
                const currentY = parseFloat(monsterDiv.style.top) || 0;
                const maxOffset = 30;
                const offsetX = (Math.random() * maxOffset * 2) - maxOffset;
                const offsetY = (Math.random() * maxOffset * 2) - maxOffset;

                let newX = currentX + offsetX;
                let newY = currentY + offsetY;
                newX = Math.max(0, Math.min(screenWidth - 48, newX));
                newY = Math.max(0, Math.min(screenHeight - 48, newY));

                if (newX < previousX) {
                    spriteDiv.style.transform = 'scaleX(1)';
                } else if (newX > previousX) {
                    spriteDiv.style.transform = 'scaleX(-1)';
                }
                previousX = newX;

                monsterDiv.style.transition = 'left 2s, top 2s';
                monsterDiv.style.left = `${newX}px`;
                monsterDiv.style.top = `${newY}px`;
            }, movementInterval);

            // Click event to show monster stats
            monsterDiv.addEventListener('click', () => {
                statName.textContent = userMonster.name;
                statStage.textContent = userMonster.monster.stage;
                statStrength.textContent = `Strength: ${userMonster.strength}`;
                statAgility.textContent = `Agility: ${userMonster.agility}`;
                statDefense.textContent = `Defense: ${userMonster.defense}`;
                statMind.textContent = `Mind: ${userMonster.mind}`;

                statsPanel.classList.remove('hidden');
                container.classList.add('rounded-b-none');
            });
        });

        // Close button event
        closeStatsButton.addEventListener('click', () => {
            statsPanel.classList.add('hidden');
            container.classList.remove('rounded-b-none');
        });

        const style = document.createElement('style');
        style.innerHTML = `
            .monster {
                position: relative;
                cursor: pointer;
            }
            .monster .sprite {
                transform-origin: center;
            }
            .monster .tooltip {
                visibility: hidden;
                background-color: black;
                color: white;
                text-align: center;
                padding: 4px 8px;
                border-radius: 4px;
                position: absolute;
                bottom: 110%;
                left: 50%;
                transform: translateX(-50%);
                white-space: nowrap;
                z-index: 1;
                opacity: 0;
                transition: opacity 0.3s;
            }
            .monster:hover .tooltip {
                visibility: visible;
                opacity: 1;
            }
            #stats-panel {
                max-height: 300px;
                overflow-y: auto;
                animation: slideIn 0.3s ease-out;
            }
            @keyframes slideIn {
                from {
                    transform: translateY(20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            #monster-container.rounded-b-none {
                border-bottom-left-radius: 0 !important;
                border-bottom-right-radius: 0 !important;
            }
            #close-stats {
                font-size: 2rem;
                cursor: pointer;
            }
            #stat-stats {
                display: flex;
                gap: 5px;
                align-items: center;
            }
        `;
        document.head.appendChild(style);
    </script>
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