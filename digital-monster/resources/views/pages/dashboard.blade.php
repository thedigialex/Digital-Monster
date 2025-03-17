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
                <x-fonts.paragraph id="stat-stage"><span></span></x-fonts.paragraph>
                <x-buttons.primary id="close-stats" label="Close" icon="fa-x" />
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="bg-primary p-4 rounded-md md:w-1/3">
                    <div class="flex justify-between w-full gap-4">
                        <div>
                            <x-fonts.paragraph>Hunger</x-fonts.paragraph>
                            <div class="hunger-icons">
                                <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                                <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                                <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                                <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                            </div>
                        </div>
                        <x-container.modal name="user-items" title="Inventory" focusable>
                            <x-slot name="button">
                                <x-buttons.primary id="close-items" label="Inventory" icon="fa-briefcase" @click="open = true" />
                            </x-slot>
                            <div class="p-2"
                                style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
                                <div class="flex flex-wrap justify-center items-center gap-4 overflow-y-auto" style="max-height: 40vh;">
                                    @foreach ($userItems as $userItem)
                                    <div class="flex flex-col items-center w-28 p-2 bg-primary border-2 border-secondary rounded-md">
                                        <div class="relative w-24 h-24 border-2 border-secondary rounded-md overflow-hidden bg-primary">
                                            <button class="useItem w-full h-full"
                                                data-item='{{ json_encode($userItem) }}'
                                                style="background: url('/storage/{{ $userItem->item->image }}') no-repeat; background-size: cover; background-position: 0 0;">
                                            </button>
                                            <span class="absolute bottom-1 right-1 bg-accent text-text text-xs px-2 py-1 rounded-md">
                                                {{ $userItem->quantity }}
                                            </span>
                                        </div>
                                        <x-fonts.paragraph> {{ $userItem->item->name }}</x-fonts.paragraph>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="flex justify-center items-center space-x-4 py-6">
                                    <div class="relative w-16 h-16 p-2">
                                        <div id="item-sprite" class="w-full h-full"></div>
                                    </div>
                                    <div class="relative w-16 h-16 p-2">
                                        <div id="monster-item-sprite" class="w-full h-full"></div>
                                    </div>
                                </div>
                            </div>
                        </x-container.modal>
                    </div>
                    <div class="pt-2">
                        <x-fonts.paragraph>Energy</x-fonts.paragraph>
                        <div class="w-full bg-secondary rounded-md h-4 relative">
                            <div id="energy-bar" class="bg-success h-4 rounded-md transition-all duration-300"></div>
                        </div>
                    </div>
                    <div class="pt-2">
                        <x-fonts.paragraph id="stat-stats" class="flex flex-wrap w-full md:flex-row flex-col md:space-x-4 space-y-2 md:space-y-0 text-text">
                            <span id="stat-strength" class="flex-1 text-center">Strength</span>
                            <span id="stat-agility" class="flex-1 text-center">Agility</span>
                            <span id="stat-defense" class="flex-1 text-center">Defense</span>
                            <span id="stat-mind" class="flex-1 text-center">Mind</span>
                        </x-fonts.paragraph>
                    </div>
                </div>
                <div class="bg-primary rounded-md md:w-2/3">
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

                        <div class="p-2"
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
        let interval;
        let userEquipment;
        let activeUserMonster;
        let training = false;
        let progress = 0;
        let direction = 1;
        let monsterImage;
        let monsterAnimationInterval;
        let secondaryImage;
        let secondaryAnimationInterval;

        const statsPanel = document.getElementById('stats-panel');
        const container = document.getElementById('monster-container');

        function updateStats() {
            document.getElementById('energy-bar').style.width = `${(activeUserMonster.energy / activeUserMonster.max_energy) * 100}%`;
            const hungerIcons = document.querySelectorAll('.hunger-icons i');

            hungerIcons.forEach((icon, index) => {
                if (index < activeUserMonster.hunger) {
                    icon.classList.add('text-accent');
                    icon.classList.remove('text-secondary');
                } else {
                    icon.classList.add('text-secondary');
                    icon.classList.remove('text-accent');
                }
            });

            document.querySelector('#stat-name span').textContent = activeUserMonster.name;
            document.querySelector('#stat-stage span').textContent = activeUserMonster.monster.stage;
            document.getElementById('stat-strength').textContent = `Strength: ${activeUserMonster.strength}`;
            document.getElementById('stat-agility').textContent = `Agility: ${activeUserMonster.agility}`;
            document.getElementById('stat-defense').textContent = `Defense: ${activeUserMonster.defense}`;
            document.getElementById('stat-mind').textContent = `Mind: ${activeUserMonster.mind}`;
        }

        function updateMainAnimation() {
            let frameIndex = 0;
            let frames = activeUserMonster.energy == 0 ? [0, 7, 7] : [0, 1, 2];
            clearInterval(activeUserMonster.mainAnimationInterval);
            activeUserMonster.mainAnimationInterval = setInterval(() => {
                index = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                activeUserMonster.spriteDiv.style.backgroundPositionX = `-${index * 48}px`;
            }, 400 + Math.random() * 400);
        }

        function getMonsterImage(userMonster) {
            if (['Fresh', 'Child'].includes(userMonster.monster.stage)) {
                return `url(/storage/${userMonster.monster.image_0})`;
            }
            const imageMap = {
                "Virus": userMonster.monster.image_1,
                "Vaccine": userMonster.monster.image_2
            };
            return `url(/storage/${imageMap[userMonster.type] || userMonster.monster.image_0})`;
        }

        function setTrainingButton() {
            const trainingButton = document.getElementById('trainingButton');
            trainingButton.disabled = false;
            trainingButton.textContent = 'Start';
            if (activeUserMonster.energy == 0) {
                trainingButton.disabled = true;
                trainingButton.textContent = 'No Energy';
            }
        }

        function monsterAnimation(frames) {
            let frameIndex = 0;
            monsterImage.style.backgroundImage = getMonsterImage(activeUserMonster);
            clearInterval(monsterAnimationInterval);
            monsterAnimationInterval = setInterval(() => {
                monsterIndex = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                monsterImage.style.backgroundPositionX = `-${monsterIndex * 48}px`;
            }, 400);
        }

        function secondaryAnimation(frames, speed) {
            let frameIndex = 0;
            clearInterval(secondaryAnimationInterval);
            secondaryAnimationInterval = setInterval(() => {
                secondaryIndex = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                secondaryImage.style.backgroundPositionX = `-${secondaryIndex * 48}px`;
            }, speed);
        }

        JSON.parse(container.getAttribute('data-monsters')).forEach(userMonster => {
            const monsterDiv = document.createElement('div');
            monsterDiv.className = 'monster';
            Object.assign(monsterDiv.style, {
                width: '48px',
                height: '48px',
                position: 'absolute'
            });

            const spriteDiv = document.createElement('div');
            spriteDiv.className = 'sprite';
            Object.assign(spriteDiv.style, {
                width: '100%',
                height: '100%',
                backgroundSize: '480px 48px'
            });

            spriteDiv.style.backgroundImage = getMonsterImage(userMonster);

            const tooltip = document.createElement('span');
            tooltip.className = 'tooltip';
            tooltip.innerText = userMonster.name;

            monsterDiv.append(spriteDiv, tooltip);
            container.appendChild(monsterDiv);

            let frameIndex = 0;
            let frames = userMonster.energy == 0 ? [0, 7, 7] : [0, 1, 2];
            spriteDiv.style.backgroundImage = getMonsterImage(userMonster);
            mainAnimationInterval = setInterval(() => {
                index = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                spriteDiv.style.backgroundPositionX = `-${index * 48}px`;
            }, 400 + Math.random() * 400);

            userMonster.mainAnimationInterval = mainAnimationInterval;
            userMonster.monsterDiv = monsterDiv;
            userMonster.spriteDiv = spriteDiv;

            let previousX = 0;
            Object.assign(monsterDiv.style, {
                left: `${Math.random() * (container.offsetWidth - 48)}px`,
                top: `${Math.random() * (container.offsetHeight - 48)}px`
            });

            setInterval(() => {
                if (userMonster.sleep_time == null && userMonster.energy > 0) {
                    let newX = parseFloat(monsterDiv.style.left) + (Math.random() * 60 - 30);
                    let newY = parseFloat(monsterDiv.style.top) + (Math.random() * 60 - 30);

                    newX = Math.max(0, Math.min(container.offsetWidth - 48, newX));
                    newY = Math.max(0, Math.min(container.offsetHeight - 48, newY));

                    spriteDiv.style.transform = newX < previousX ? 'scaleX(1)' : 'scaleX(-1)';
                    previousX = newX;

                    Object.assign(monsterDiv.style, {
                        transition: 'left 2s, top 2s',
                        left: `${newX}px`,
                        top: `${newY}px`
                    });
                }
            }, 4000 + Math.random() * 2000);

            monsterDiv.addEventListener('click', () => {
                if (activeUserMonster) {
                    activeUserMonster.monsterDiv.classList.remove('clicked');
                }
                activeUserMonster = userMonster;
                monsterDiv.classList.add('clicked');
                statsPanel.classList.remove('hidden');
                container.classList.add('rounded-b-none');
                updateStats();
            });
        });

        document.getElementById('close-stats').addEventListener('click', () => {
            statsPanel.classList.add('hidden');
            container.classList.remove('rounded-b-none');
            if (activeUserMonster) {
                activeUserMonster.monsterDiv.classList.remove('clicked');
            }
        });

        document.querySelectorAll('.openTraining').forEach(button => {
            button.addEventListener('click', function() {
                userEquipment = JSON.parse(this.getAttribute('data-equipment'));
                setTrainingButton();

                monsterImage = document.getElementById('monster-sprite');
                secondaryImage = document.getElementById('equipment-sprite');
                secondaryImage.style.backgroundImage = `url(/storage/${userEquipment.equipment.image})`;
                clearInterval(secondaryAnimationInterval);

                monsterAnimation([0, 1]);
            });
        });

        document.querySelectorAll('.useItem').forEach(button => {
            button.addEventListener('click', function() {
                userItem = JSON.parse(this.getAttribute('data-item'));

                monsterImage = document.getElementById('monster-item-sprite');
                secondaryImage = document.getElementById('item-sprite');
                secondaryImage.style.backgroundImage = `url(/storage/${userItem.item.image})`;

                monsterAnimation([1, 2]);
                secondaryAnimation([0, 1, 2, 3], 800);

                
                const data = {
                    user_item_id: userItem.id,
                    user_monster_id: activeUserMonster.id
                };
                
                fetch("{{ route('monster.item') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json())
                    .then(result => {
                        updateStats();
                        updateMainAnimation();
                        console.log("data updated:", result);
                    });
            });
        });

        document.getElementById('trainingButton').addEventListener('click', function() {
            if (!training) {
                progress = 0;
                direction = 1;

                monsterAnimation([3, 4]);
                secondaryAnimation([0, 1, 2, 3], 400);

                interval = setInterval(() => {
                    progress += 5 * direction;
                    if (progress >= 100 || progress <= 0) {
                        direction *= -1;
                    }
                    document.getElementById('progress-bar').style.width = progress + "%";
                }, 100);
            } else {
                clearInterval(interval);
                clearInterval(secondaryAnimationInterval);

                let trainingFrames = progress < 60 ? [0, 7] : [0, 8];

                monsterAnimation(trainingFrames);

                const data = {
                    percentage: progress,
                    user_equipment_id: userEquipment.id,
                    user_monster_id: activeUserMonster.id
                };

                fetch("{{ route('monster.train') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json())
                    .then(result => {
                        //update the activeUserMonster from the result
                        activeUserMonster.energy -= 1;
                        setTrainingButton();
                        updateStats();
                        updateMainAnimation();
                        console.log("Training data updated:", result);
                    });
            }
            training = !training;
        });
    </script>
</x-app-layout>