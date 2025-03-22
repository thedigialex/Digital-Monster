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
                                <x-buttons.primary id="openItems" label="Inventory" icon="fa-briefcase" @click="open = true" />
                            </x-slot>

                            <div class="flex flex-col justify-center items-center bg-cover bg-center"
                                style="background-image: url('/images/background-dashboard.png'); height: 30vh;">
                                <div class="flex flex-wrap justify-center items-center gap-4 overflow-y-auto" id="item-selection">
                                    @foreach ($userItems as $userItem)
                                    <div class="flex flex-col items-center w-28 p-2 bg-secondary border-2 border-accent rounded-md">
                                        <div class="w-24 h-24 p-2 rounded-md bg-primary">
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
                                <div id="status-section" class="flex justify-center items-center" style="display: none;">
                                    <div class="p-2 bg-primary rounded-md">
                                        <x-fonts.paragraph id="status-text" class="text-text">Monster is full</x-fonts.paragraph>
                                    </div>
                                </div>
                                <div id="item-usage-section" class="flex justify-center items-center gap-4 p-2 w-full" style="display: none;">
                                    <div class="w-16 h-16 p-2">
                                        <div id="item-sprite" class="w-full h-full"></div>
                                    </div>
                                    <div class="w-16 h-16 p-2">
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
                            <span id="stat-strength" class="flex-1 text-center">Strength<br><span></span></span>
                            <span id="stat-agility" class="flex-1 text-center">Agility<br><span></span></span>
                            <span id="stat-defense" class="flex-1 text-center">Defense<br><span></span></span>
                            <span id="stat-mind" class="flex-1 text-center">Mind<br><span></span></span>
                        </x-fonts.paragraph>
                    </div>
                </div>
                <div class="bg-primary rounded-md md:w-2/3 flex items-center justify-center">
                    <x-container.modal name="user-monster-training" title="Training" focusable>
                        <x-slot name="button">
                            <div class="flex justify-center my-4">
                                <button id="evolutionButton" class="w-[150px] relative inline-flex active:scale-90 overflow-hidden rounded-md p-1 focus:outline-none">
                                    <span class="absolute inset-[-1000%] animate-spin bg-[conic-gradient(from_90deg_at_50%_50%,#333_0%,#545454_50%,#e47e00_100%)]">
                                    </span>
                                    <span class="inline-flex h-full w-full items-center justify-center rounded-md bg-secondary p-4 text-text backdrop-blur-3xl">
                                        Evolve
                                    </span>
                                </button>
                            </div>
                            <div class="flex flex-wrap justify-center gap-4 items-center">
                                @foreach ($userEquipment as $userEquipment)
                                <x-buttons.square class="openTraining w-[150px]" @click="open = true"
                                    data-equipment='{{ json_encode($userEquipment) }}'
                                    text="{{ $userEquipment->equipment->stat }}" />
                                @endforeach
                            </div>
                            <div class="flex justify-center my-4">
                                <x-buttons.square id="sleepButton" class="w-[150px]"
                                    data-equipment='{{ json_encode($userEquipmentLight) }}'
                                    text="{{ $userEquipmentLight->equipment->stat }}" />
                            </div>
                        </x-slot>

                        <div class="flex flex-col justify-center items-center bg-cover bg-center"
                            style="background-image: url('/images/background-dashboard.png'); height: 30vh;">
                            <div id="training-section" class="flex flex-col justify-center items-center gap-4 p-2 w-full">
                                <div class="flex justify-center items-center">
                                    <div class="w-16 h-16 p-2">
                                        <div id="equipment-sprite" class="w-full h-full"></div>
                                    </div>
                                    <div class="w-16 h-16 p-2">
                                        <div id="monster-sprite" class="w-full h-full"></div>
                                    </div>
                                </div>

                                <div class="w-full h-8 bg-secondary rounded-md">
                                    <div id="progress-bar" class="h-full bg-accent w-0 rounded-md"></div>
                                </div>

                                <button id="trainingButton" class="px-4 py-2 bg-red-500 text-text rounded-md">Start</button>
                            </div>

                            <div id="sleep-section" class="flex justify-center items-center" style="display: none;">
                                <div class="p-2 bg-primary rounded-md">
                                    <x-fonts.paragraph class="text-text">Monster is sleeping</x-fonts.paragraph>
                                </div>
                            </div>
                        </div>
                    </x-container.modal>
                </div>
            </div>
        </div>
    </x-container>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            function updateItemSections() {
                const itemSelectionSection = document.getElementById('item-selection');
                const statusSection = document.getElementById('status-section');
                const statusText = document.getElementById('status-text');

                if (activeUserMonster.hunger != 4 && activeUserMonster.sleep_time == null) {
                    statusSection.style.display = 'none';
                    itemSelectionSection.style.display = 'flex';
                } else {
                    statusSection.style.display = 'flex';
                    statusText.textContent = 'Monster is full';
                    if (activeUserMonster.sleep_time != null) {
                        statusText.textContent = 'Monster is sleeping';
                    }
                    itemSelectionSection.style.display = 'none';
                }
            }

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
                document.querySelector('#stat-strength span').textContent = activeUserMonster.strength;
                document.querySelector('#stat-agility span').textContent = activeUserMonster.agility;
                document.querySelector('#stat-defense span').textContent = activeUserMonster.defense;
                document.querySelector('#stat-mind span').textContent = activeUserMonster.mind;

                document.getElementById("evolutionButton").classList.add("hidden");
                if (activeUserMonster.monster.evo_requirement != 0 && activeUserMonster.evo_points == activeUserMonster.monster.evo_requirement) {
                    document.getElementById("evolutionButton").classList.remove("hidden");
                }
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
                    position: 'absolute',
                });

                const spriteDiv = document.createElement('div');
                spriteDiv.className = 'sprite';
                Object.assign(spriteDiv.style, {
                    width: '100%',
                    height: '100%',
                    backgroundSize: '480px 48px',
                });

                const shadowDiv = document.createElement('div');
                shadowDiv.className = 'monster-shadow';
                Object.assign(shadowDiv.style, {
                    width: '48px',
                    height: '12px',
                    position: 'absolute',
                    bottom: '-6px',
                    left: '0',
                    backgroundColor: '#333333',
                    borderRadius: '32px',
                    opacity: '0.25',
                });

                const tooltip = document.createElement('span');
                tooltip.className = 'tooltip';
                tooltip.innerText = userMonster.name;

                userMonster.monsterDiv = monsterDiv;
                userMonster.spriteDiv = spriteDiv;

                let previousX = 0;

                Object.assign(monsterDiv.style, {
                    left: `${Math.random() * (container.offsetWidth - 48)}px`,
                    top: `${Math.random() * (container.offsetHeight - 48)}px`
                });

                monsterDiv.append(spriteDiv, tooltip, shadowDiv);
                container.appendChild(monsterDiv);

                userMonster.updateAnimation = function() {
                    spriteDiv.style.backgroundImage = getMonsterImage(userMonster);

                    if (this.mainAnimationInterval) {
                        clearInterval(this.mainAnimationInterval);
                    }
                    let frameIndex = 0;
                    let frames = this.energy == 0 ? [0, 7, 7] : [0, 1, 2];

                    if (this.sleep_time != null) {
                        frames = [5, 6];
                    }

                    this.mainAnimationInterval = setInterval(() => {
                        let index = frames[frameIndex];
                        frameIndex = (frameIndex + 1) % frames.length;
                        this.spriteDiv.style.backgroundPositionX = `-${index * 48}px`;
                    }, 400 + Math.random() * 400);

                    this.updateMovement();
                };

                userMonster.updateMovement = function() {
                    if (this.movementInterval) {
                        clearInterval(this.movementInterval);
                    }

                    this.movementInterval = setInterval(() => {
                        if (this.sleep_time == null && this.energy > 0) {
                            let newX = parseFloat(this.monsterDiv.style.left) + (Math.random() * 60 - 30);
                            let newY = parseFloat(this.monsterDiv.style.top) + (Math.random() * 60 - 30);

                            newX = Math.max(0, Math.min(container.offsetWidth - 48, newX));
                            newY = Math.max(0, Math.min(container.offsetHeight - 48, newY));

                            this.spriteDiv.style.transform = newX < previousX ? 'scaleX(1)' : 'scaleX(-1)';
                            previousX = newX;

                            Object.assign(this.monsterDiv.style, {
                                transition: 'left 2s, top 2s',
                                left: `${newX}px`,
                                top: `${newY}px`
                            });
                        }
                    }, 4000 + Math.random() * 2000);
                };

                userMonster.updateUserMonster = function(updatedMonster) {
                    Object.assign(this, updatedMonster);
                    this.updateAnimation();
                };

                userMonster.updateAnimation();

                let isDragging = false;
                let offsetX = 0;
                let offsetY = 0;

                monsterDiv.addEventListener('mousedown', (e) => {
                    isDragging = true;
                    offsetX = e.clientX - monsterDiv.offsetLeft;
                    offsetY = e.clientY - monsterDiv.offsetTop;

                    monsterDiv.style.transition = 'none';
                });

                document.addEventListener('mousemove', (e) => {
                    if (isDragging) {
                        const mouseX = e.clientX;
                        const mouseY = e.clientY;

                        let newLeft = mouseX - offsetX;
                        let newTop = mouseY - offsetY;

                        newLeft = Math.max(0, Math.min(container.offsetWidth - 48, newLeft));
                        newTop = Math.max(0, Math.min(container.offsetHeight - 48, newTop));

                        monsterDiv.style.left = `${newLeft}px`;
                        monsterDiv.style.top = `${newTop}px`;
                    }
                });

                document.addEventListener('mouseup', () => {
                    if (isDragging) {
                        isDragging = false;
                        monsterDiv.style.transition = 'left 0.3s, top 0.3s';
                    }
                });

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
                    const trainingSection = document.getElementById('training-section');
                    const sleepSection = document.getElementById('sleep-section');

                    if (activeUserMonster.sleep_time == null) {
                        sleepSection.style.display = 'none';
                        trainingSection.style.display = 'flex';
                    } else {
                        sleepSection.style.display = 'flex';
                        trainingSection.style.display = 'none';
                    }

                    userEquipment = JSON.parse(this.getAttribute('data-equipment'));
                    setTrainingButton();

                    monsterImage = document.getElementById('monster-sprite');
                    secondaryImage = document.getElementById('equipment-sprite');
                    secondaryImage.style.backgroundImage = `url(/storage/${userEquipment.equipment.image})`;
                    clearInterval(secondaryAnimationInterval);

                    monsterAnimation([0, 1]);
                });
            });

            document.getElementById('openItems').addEventListener('click', function() {
                updateItemSections();
            });

            document.querySelectorAll('.useItem').forEach(button => {
                button.addEventListener('click', function() {
                    const itemSelectionSection = document.getElementById('item-selection');
                    itemSelectionSection.style.display = 'none';
                    const animationSection = document.getElementById('item-usage-section');
                    animationSection.style.display = 'flex';

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
                            activeUserMonster.updateUserMonster(result.userMonster);
                            updateStats();
                            if (result.userItemQuantity == 0) {
                                this.closest('.flex.flex-col.items-center').remove();
                            } else {
                                this.nextElementSibling.textContent = result.userItemQuantity;
                            }

                            setTimeout(() => {
                                itemSelectionSection.style.display = 'flex';
                                animationSection.style.display = 'none';
                                updateItemSections();
                            }, 3800);
                        });
                });
            });

            document.getElementById('trainingButton').addEventListener('click', function() {
                if (!training) {
                    const trainingButton = document.getElementById('trainingButton');
                    trainingButton.textContent = 'Stop';

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
                            activeUserMonster.updateUserMonster(result.userMonster);
                            setTrainingButton();
                            updateStats();
                        });
                }
                training = !training;
            });

            document.getElementById('sleepButton').addEventListener('click', function() {
                userEquipment = JSON.parse(this.getAttribute('data-equipment'));
                const data = {
                    user_equipment_id: userEquipment.id,
                    user_monster_id: activeUserMonster.id
                };

                fetch("{{ route('monster.sleep') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json())
                    .then(result => {
                        activeUserMonster.updateUserMonster(result.userMonster);
                        updateStats();
                    });
            });

            document.getElementById('evolutionButton').addEventListener('click', function() {
                const data = {
                    user_monster_id: activeUserMonster.id
                };

                fetch("{{ route('monster.evolve') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json())
                    .then(result => {
                        const currentMonsterDiv = activeUserMonster.monsterDiv;
                        const spriteDiv = activeUserMonster.spriteDiv;

                        spriteDiv.classList.add('flicker');
                        currentMonsterDiv.classList.add("evolution-animation");

                        setTimeout(() => {
                            activeUserMonster.updateUserMonster(result.userMonster);
                            updateStats();
                            spriteDiv.classList.remove('flicker');
                            spriteDiv.style.backgroundImage = getMonsterImage(activeUserMonster);
                            setTimeout(() => {
                                spriteDiv.style.transition = "opacity 0.5s";
                                spriteDiv.style.opacity = 1;
                                currentMonsterDiv.classList.remove("evolution-animation");
                            }, 50);
                        }, 1500);
                    });
            });
        });
    </script>
</x-app-layout>