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
            </div>
        </x-slot>

        <div
            id="monster-container"
            class="relative w-full h-[500px] overflow-hidden rounded-b-md shadow-lg"
            data-monsters='@json($userMonsters)'
            style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
        </div>

        <div id="stats-panel" class="hidden bg-secondary border-primary border-t-4 p-4 shadow-lg rounded-b-md">
            <div class="flex justify-between items-center pb-4">
                <x-fonts.sub-header id="stat-name">Name: <span></span></x-fonts.sub-header>
                <x-buttons.primary id="close-stats" label="Close" icon="fa-x" />
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="bg-primary p-4 rounded-md md:w-1/3">
                    <x-fonts.paragraph id="stat-stage"><strong>Stage:</strong> <span></span></x-fonts.paragraph>
                    <x-fonts.paragraph id="stat-stats" class="flex flex-wrap w-full md:flex-row flex-col md:space-x-4 space-y-2 md:space-y-0 text-text">
                        <span id="stat-strength" class="flex-1 text-center">Strength</span>
                        <span id="stat-agility" class="flex-1 text-center">Agility</span>
                        <span id="stat-defense" class="flex-1 text-center">Defense</span>
                        <span id="stat-mind" class="flex-1 text-center">Mind</span>
                    </x-fonts.paragraph>
                </div>
                <div class="bg-primary p-4 rounded-md md:w-2/3">
                    <x-container.modal name="user-monster-training" title="Training" focusable>
                        <x-slot name="button">
                            <div class="flex flex-wrap justify-center gap-4 items-center">
                                @foreach ($userEquipment as $userEquipment)
                                <x-buttons.square class="openTraining w-[150px]" @click="open = true"
                                    data-equipment='{{ json_encode($userEquipment) }}'
                                    text="{{ $userEquipment->equipment->stat }}" />
                                @endforeach
                            </div>
                            <div class="flex justify-center my-4">
                                <x-buttons.square class="openTraining w-[150px]" @click="open = true"
                                    data-equipment='{{ json_encode($userEquipmentLight) }}'
                                    text="{{ $userEquipmentLight->equipment->stat }}" />
                            </div>
                        </x-slot>

                        <div class="p-4"
                            style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
                            <div class="flex justify-center items-center space-x-4 py-6">
                                <div class="relative w-16 h-16 p-2">
                                    <div id="equipment-sprite" class="w-full h-full"></div>
                                </div>
                                <div class="relative w-16 h-16 p-2">
                                    <div id="monster-sprite" class="w-full h-full"></div>
                                </div>
                            </div>

                            <div class="progress-container w-full h-8 bg-secondary mt-6 relative overflow-hidden rounded-lg">
                                <div id="progress-bar" class="h-full bg-accent w-0"></div>
                            </div>

                            <div class="flex justify-center mt-4">
                                <button id="trainingButton" class="px-4 py-2 bg-red-500 text-white rounded-lg ml-2">Start</button>
                            </div>
                        </div>
                    </x-container.modal>
                </div>
            </div>
        </div>
    </x-container>

    <script>
        let activeUserMonster;
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

            if (userMonster.monster.stage !== 'Fresh' && userMonster.monster.stage !== 'Child') {
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
            tooltip.innerText = userMonster.name;

            monsterDiv.appendChild(spriteDiv);
            monsterDiv.appendChild(tooltip);
            container.appendChild(monsterDiv);

            let frames = userMonster.energy == 0 ? [0, 7, 7] : [0, 1, 2];
            let frameIndex = Math.floor(Math.random() * frames.length);
            const animationIntervalTime = 200 + Math.random() * 400;

            let animationInterval = setInterval(() => {
                frameIndex = (frameIndex + 1) % frames.length;
                spriteDiv.style.backgroundPositionX = `-${frames[frameIndex] * 48}px`;
            }, animationIntervalTime);

            let previousX = parseFloat(monsterDiv.style.left) || 0;
            const x = Math.random() * (screenWidth - 48);
            const y = Math.random() * (screenHeight - 48);
            monsterDiv.style.left = `${x}px`;
            monsterDiv.style.top = `${y}px`;

            const movementInterval = 4000 + Math.random() * 2000;
            setInterval(() => {
                if (userMonster.sleep_time == null && userMonster.energy > 0) {
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
                }
            }, movementInterval);

            monsterDiv.addEventListener('click', () => {
                if (activeUserMonster) {
                    activeUserMonster.monsterDiv.classList.remove('clicked');
                }

                activeUserMonster = userMonster;

                monsterDiv.classList.add('clicked');

                statName.textContent = userMonster.name;
                statStage.textContent = userMonster.monster.stage;
                statStrength.textContent = `Strength: ${userMonster.strength}`;
                statAgility.textContent = `Agility: ${userMonster.agility}`;
                statDefense.textContent = `Defense: ${userMonster.defense}`;
                statMind.textContent = `Mind: ${userMonster.mind}`;

                statsPanel.classList.remove('hidden');
                container.classList.add('rounded-b-none');
            });

            userMonster.updateEnergy = function(newEnergy) {
                this.energy = newEnergy;
                updateAnimation();
            };

            userMonster.monsterDiv = monsterDiv;
        });

        closeStatsButton.addEventListener('click', () => {
            statsPanel.classList.add('hidden');
            container.classList.remove('rounded-b-none');
            if (activeUserMonster) {
                activeUserMonster.monsterDiv.classList.remove('clicked');
            }
        });


        //training
        let progress = 0;
        let direction = 1;
        let interval;
        let animationInterval;
        let monsterFrame = 0;
        const monsterSprite = document.getElementById('monster-sprite');
        let userEquipment;
        let equipmentInterval;
        let equipmentAnimationInterval;
        let equipmentFrame = 0;
        const equipmentSprite = document.getElementById('equipment-sprite');

        let training = false;

        document.querySelectorAll('.openTraining').forEach(button => {
            button.addEventListener('click', function() {
                userEquipment = JSON.parse(this.getAttribute('data-equipment'));
                const trainingButton = document.getElementById('trainingButton');
                trainingButton.disabled = false;
                trainingButton.textContent = 'Start';
                if (activeUserMonster.energy == 0) {
                    trainingButton.disabled = true;
                    trainingButton.textContent = 'No Energy';
                }

                monsterFrame = 0;
                monsterSprite.style.backgroundImage = `url(/storage/${activeUserMonster.monster.image_0})`;
                equipmentFrame = 0;
                equipmentSprite.style.backgroundImage = `url(/storage/${userEquipment.equipment.image})`;

                if (activeUserMonster.monster.stage !== 'Fresh' && activeUserMonster.monster.stage !== 'Child') {
                    switch (activeUserMonster.type) {
                        case "Virus":
                            monsterSprite.style.backgroundImage = `url(/storage/${activeUserMonster.monster.image_1})`;
                            break;
                        case "Vaccine":
                            monsterSprite.style.backgroundImage = `url(/storage/${activeUserMonster.monster.image_2})`;
                            break;
                    }
                }

                clearInterval(animationInterval);
                animationInterval = setInterval(() => {
                    monsterFrame = (monsterFrame + 1) % 3;
                    monsterSprite.style.backgroundPositionX = `-${monsterFrame * 48}px`;
                }, 400);
                clearInterval(equipmentAnimationInterval);
            });
        });

        document.getElementById('trainingButton').addEventListener('click', function() {
            if (!training) {
                progress = 0;
                direction = 1;

                startAnimation([3, 4]);
                startAnimation(null, true);

                interval = setInterval(() => {
                    progress += 5 * direction;
                    if (progress >= 100 || progress <= 0) {
                        direction *= -1;
                    }
                    document.getElementById('progress-bar').style.width = progress + "%";
                }, 100);
            } else {
                clearInterval(interval);
                clearInterval(equipmentAnimationInterval);

                let trainingFrames = [0, 8];
                if (progress < 60) {
                    trainingFrames = [0, 7];
                }

                startAnimation(trainingFrames);

                activeUserMonster.energy -= 1;
                activeUserMonster.updateEnergy(activeUserMonster.energy);

                const data = {
                    percentage: progress,
                    user_equipment_id: userEquipment.id,
                    user_monster_id: activeUserMonster.id
                };

                fetch("{{ route('train.update') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json())
                    .then(result => {
                        statStrength.textContent = `Strength: ${activeUserMonster.strength}`;
                        statAgility.textContent = `Agility: ${activeUserMonster.agility}`;
                        statDefense.textContent = `Defense: ${activeUserMonster.defense}`;
                        statMind.textContent = `Mind: ${activeUserMonster.mind}`;
                        console.log("Training data updated:", result);
                    });
            }
            training = !training;
            if (activeUserMonster.energy > 0) {
                this.textContent = training ? 'Stop' : 'Start';
            } else {
                this.disabled = true;
                this.textContent = 'No Energy';
            }
        });

        function startAnimation(frames, isEquipment = false) {
            let frameIndex = 0;

            if (isEquipment) {
                clearInterval(equipmentAnimationInterval);
                equipmentAnimationInterval = setInterval(() => {
                    equipmentFrame = (equipmentFrame + 1) % 3;
                    equipmentSprite.style.backgroundPositionX = `-${equipmentFrame * 48}px`;
                }, 400);
            } else {
                clearInterval(animationInterval);
                animationInterval = setInterval(() => {
                    monsterFrame = frames[frameIndex];
                    frameIndex = (frameIndex + 1) % frames.length;
                    monsterSprite.style.backgroundPositionX = `-${monsterFrame * 48}px`;
                }, 400);
            }
        }
    </script>
</x-app-layout>