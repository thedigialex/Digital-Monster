<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            DigiGarden
        </x-fonts.sub-header>
        <div class="flex flex-row gap-4">
            <a href="{{ route('digigarden.chart') }}">
                <x-buttons.button type="edit" icon=" fa-diagram-project" label="Chart" />
            </a>
            <a href="{{ route('digigarden.info') }}">
                <x-buttons.button type="edit" icon="fa-circle-info" label="Info" />
            </a>
        </div>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                DigiGarden {{ $count }}
            </x-fonts.sub-header>
            <x-container.modal name="user-items" title="Settings">
                <x-slot name="button">
                    <x-buttons.button type="edit" icon="fa-gear" label="Settings" id="openMenu" @click="open = true" />
                </x-slot>
                <x-container.tab-div class="pt-2 px-2 justify-center bg-secondary">
                    <x-buttons.tab id="showBackgrounds" class="bg-accent text-secondary p-2" label="Backgrounds" />
                </x-container.tab-div>
                <div class="flex flex-col justify-center items-center bg-cover bg-center rounded-b-md"
                    style="background-image: url('{{ asset($background) }}'); height: 40vh;">
                    <x-alerts.spinner id="loading-section-background"></x-alerts.spinner>
                    <div id="backgrounds" class="flex flex-wrap justify-center items-center gap-4 overflow-y-auto">
                        @foreach ($userBackgrounds as $userBackground)
                        <div class="background-div flex flex-col items-center w-36 p-2 bg-secondary border-2 border-accent rounded-md"
                            data-background-id="{{ $userBackground->id }}">
                            <div class="w-24 h-24 p-2 rounded-md bg-primary">
                                <button class="useBackground w-full h-full"
                                    data-background='{{ json_encode($userBackground) }}' data-equipped_id='{{ json_encode($background_id) }}'
                                    style="background: url('/storage/{{ $userBackground->item->image }}') no-repeat; background-size: cover; background-position: 0 0;">
                                </button>
                            </div>
                            <x-fonts.paragraph class="background-p text-text"> {{ $userBackground->item->name }}</x-fonts.paragraph>
                        </div>
                        @endforeach
                        @if($userBackgrounds->isEmpty())
                        <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">No Additional Backgrounds</x-fonts.paragraph>
                        @endif
                    </div>
                </div>
            </x-container.modal>
        </x-slot>
        <div
            id="monster-container"
            class="relative w-full rounded-b-md shadow-lg bg-cover bg-center h-[60vh]"
            data-monsters='@json($userMonsters)'
            style="background-image: url('{{ asset($background) }}');">
        </div>
        <div id="stats-panel" class="hidden bg-secondary border-primary border-t-4 p-4 shadow-lg rounded-b-md">
            <div class="flex justify-between items-center pb-4">
                <x-fonts.sub-header id="stat-name-wrapper">
                    <span id="stat-level"><span></span> </span>
                    <span id="stat-name" class="ml-2"><span></span> </span>
                    <input type="text" id="name-input" class="hidden text-text bg-neutral focus:border-accent focus:ring-accent rounded-md" />
                    <i class="fa-solid fa-pen-to-square ml-2 cursor-pointer" id="edit-icon"></i>
                    <i class="fa-solid fa-save ml-2 cursor-pointer hidden" id="save-name-btn"></i>
                </x-fonts.sub-header>
                <x-buttons.button type="edit" id="close-stats" label="Close" icon="fa-x" />
            </div>

            <div class="bg-primary p-4 rounded-md flex flex-col gap-4">
                <div class="flex justify-end gap-4 w-full">
                    <x-buttons.button type="edit" id="sleepButton" label="Light" icon="fa-lightbulb" class="buttonContainer" />
                    <x-container.modal name="user-items" title="Inventory">
                        <x-slot name="button">
                            <x-buttons.button type="edit" id="openItems" label="Items" icon="fa-briefcase" @click="open = true" />
                        </x-slot>
                        <x-container.tab-div class="pt-2 px-2 justify-center bg-secondary">
                            <x-buttons.tab id="showItems" class="bg-accent text-secondary p-2" label="Consumable" />
                            <x-buttons.tab id="showAttacks" class="bg-secondary text-text p-2" label="Attacks" />
                            <x-buttons.tab id="showMaterials" class="bg-secondary text-text p-2" label="Materials" />
                        </x-container.tab-div>
                        <div class="flex flex-col justify-center items-center bg-cover bg-center rounded-b-md"
                            style="background-image: url('{{ asset($background) }}'); height: 40vh;">
                            <div id="items" class="flex justify-center items-center overflow-y-auto">
                                <div id="item-selection" class="flex flex-wrap justify-center items-center gap-4">
                                    @foreach ($userItems as $userItem)
                                    <x-container.item-card :data-item="$userItem" buttonClass="useItem" :bottomText="$userItem->quantity" />
                                    @endforeach
                                    @if($userItems->isEmpty())
                                    <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">No Consumables</x-fonts.paragraph>
                                    @endif
                                </div>
                                <x-fonts.paragraph id="status-text-item" class="text-text p-2 bg-primary rounded-md">Monster is full</x-fonts.paragraph>
                                <div id="item-usage-section" class="hidden flex justify-center items-center gap-4 w-full">
                                    <x-container.sprite id="item-sprite"></x-container.sprite>
                                    <x-container.sprite id="monster-item-sprite"></x-container.sprite>
                                </div>
                            </div>
                            <div id="attacks" class="hidden flex flex-wrap justify-center items-center gap-4 overflow-y-auto">
                                @foreach ($userAttacks as $userAttack)
                                <x-container.item-card :data-item="$userAttack" buttonClass="useAttack" divClass="attack-div" />
                                @endforeach
                            </div>
                            <div id="materials" class="hidden flex flex-wrap justify-center items-center gap-4 overflow-y-auto">
                                @foreach ($userMaterials as $userMaterial)
                                <x-container.item-card :data-item="$userMaterial" :bottomText='$userMaterial->quantity' />
                                @endforeach
                                @if($userMaterials->isEmpty())
                                <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">No Materials</x-fonts.paragraph>
                                @endif
                            </div>
                        </div>
                    </x-container.modal>
                </div>
                <div class="flex flex-wrap gap-4 justify-start">
                    <x-fonts.paragraph id="stat-stage">Stage: <span></span></x-fonts.paragraph>
                    <x-fonts.paragraph id="stat-steps">Steps: <span></span></x-fonts.paragraph>
                </div>
                <div class="flex gap-4 justify-between">
                    <div class="flex-1 flex flex-col gap-1">
                        <x-fonts.paragraph>Energy</x-fonts.paragraph>
                        <div class="w-full bg-secondary rounded-md h-8">
                            <div id="energy-bar" class="bg-success h-8 rounded-md transition-all duration-300"></div>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col gap-1">
                        <x-fonts.paragraph>Hunger</x-fonts.paragraph>
                        <div class="hunger-icons flex flex-row justify-evenly">
                            <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                            <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                            <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                            <i class="fa-solid fa-drumstick-bite fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4 justify-evenly bg-secondary p-4 rounded-lg">
                    <x-fonts.paragraph id="stat-strength" class="text-text flex flex-col gap-2 text-center">
                        Strength:
                        <span></span>
                    </x-fonts.paragraph>
                    <x-fonts.paragraph id="stat-agility" class="text-text flex flex-col gap-2 text-center">
                        Agility:
                        <span></span>
                    </x-fonts.paragraph>
                    <x-fonts.paragraph id="stat-defense" class="text-text flex flex-col gap-2 text-center">
                        Defense:
                        <span></span>
                    </x-fonts.paragraph>
                    <x-fonts.paragraph id="stat-mind" class="text-text flex flex-col gap-2 text-center">
                        Mind:
                        <span></span>
                    </x-fonts.paragraph>
                </div>

                <div id="evolutionButton" class="flex justify-center">
                    <button class="w-[150px] relative inline-flex hover:scale-90 active:scale-90 overflow-hidden rounded-md p-1 focus:outline-none flex justify-center">
                        <span class="absolute inset-[-1000%] animate-spin bg-[conic-gradient(from_90deg_at_50%_50%,#333_0%,#545454_50%,#e47e00_100%)]">
                        </span>
                        <span class="inline-flex h-full w-full items-center justify-center rounded-md bg-secondary p-4 text-text backdrop-blur-3xl">
                            Evolve
                        </span>
                    </button>
                </div>

                <x-container.modal name="user-monster-training" title="Training">
                    <x-slot name="button">
                        <div class="flex flex-wrap justify-evenly gap-4 buttonContainer p-4">
                            @foreach ($userEquipment as $userEquipment)
                            <x-buttons.button type="edit" class="openTraining" @click="open = true"
                                data-equipment='{{ json_encode($userEquipment) }}'
                                label="{{ $userEquipment->equipment->stat }}"
                                icon="{{ $userEquipment->equipment->icon }}" />
                            @endforeach
                        </div>
                    </x-slot>

                    <div class="flex flex-col justify-center items-center bg-cover bg-center"
                        style="background-image: url('{{ asset($background) }}'); height: 40vh;">
                        <div id="training-section" class="flex flex-col justify-center items-center gap-4 p-2 w-full">
                            <div class="flex justify-center items-center">
                                <x-container.sprite id="equipment-sprite"></x-container.sprite>
                                <x-container.sprite id="monster-sprite"></x-container.sprite>
                            </div>
                            <div class="relative w-full h-8 bg-secondary rounded-md overflow-hidden">
                                <x-fonts.paragraph id="equipment-name" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white z-10"></x-fonts.paragraph>
                                <div id="progress-bar" class="absolute inset-0 h-full bg-accent w-0 rounded-md z-0"></div>
                            </div>
                            <x-buttons.button type="edit" id="trainingButton" label="Start" icon="fa-play" />
                        </div>
                        <x-fonts.paragraph id="sleep-section" class="text-text p-2 bg-primary rounded-md">Monster is sleeping</x-fonts.paragraph>
                    </div>
                </x-container.modal>
            </div>
        </div>
    </x-container>
</x-app-layout>

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
        const nameDisplay = document.getElementById('stat-name');
        const nameInput = document.getElementById('name-input');
        const editIcon = document.getElementById('edit-icon');
        const saveBtn = document.getElementById('save-name-btn');

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

            document.querySelector('#stat-level span').textContent = 'Lvl: ' + activeUserMonster.level;
            document.querySelector('#stat-name span').textContent = activeUserMonster.name;
            document.querySelector('#stat-stage span').textContent = activeUserMonster.monster.stage;
            document.querySelector('#stat-steps span').textContent = activeUserMonster.steps;
            document.querySelector('#stat-strength span').textContent = activeUserMonster.strength;
            document.querySelector('#stat-agility span').textContent = activeUserMonster.agility;
            document.querySelector('#stat-defense span').textContent = activeUserMonster.defense;
            document.querySelector('#stat-mind span').textContent = activeUserMonster.mind;

            document.getElementById("evolutionButton").classList.add("hidden");
            if (activeUserMonster.monster.stage != "Mega" && activeUserMonster.evo_points >= activeUserMonster.monster.evo_requirement) {
                document.getElementById("evolutionButton").classList.remove("hidden");
            }

            document.querySelectorAll(".buttonContainer").forEach(el => {
                el.classList.remove("hidden");
            });

            if (activeUserMonster.monster.stage == "Egg") {
                document.querySelectorAll(".buttonContainer").forEach(el => {
                    el.classList.add("hidden");
                });
            }
        }

        function getMonsterImage(userMonster) {
            if (['Fresh', 'Child'].includes(userMonster.monster.stage)) {
                return `url(/storage/${userMonster.monster.image_0})`;
            }
            const imageMap = {
                "Vaccine": userMonster.monster.image_1,
                "Virus": userMonster.monster.image_2,
            };
            return `url(/storage/${imageMap[userMonster.type] || userMonster.monster.image_0})`;
        }

        function setTrainingButton() {
            const trainingButton = document.getElementById('trainingButton');
            const labelSpan = trainingButton.querySelector('.label');
            const icon = trainingButton.querySelector('i.icon');
            if (activeUserMonster.energy == 0) {
                trainingButton.disabled = true;
                labelSpan.textContent = 'No Energy';
                icon.className = 'icon fa fa-ban';
            } else {
                trainingButton.disabled = false;
                labelSpan.textContent = 'Start';
                icon.className = 'icon fa fa-play';
            }
        }

        function animateSprite(targetImage, frames, speed, intervalVar) {
            let frameIndex = 0;
            clearInterval(intervalVar);

            targetImage.style.backgroundPositionX = `-${frames[frameIndex] * 48}px`;

            intervalVar = setInterval(() => {
                let currentIndex = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                targetImage.style.backgroundPositionX = `-${currentIndex * 48}px`;
            }, speed);

            return intervalVar;
        }

        function showTab(tabId) {
            const tabs = ['items', 'attacks', 'materials'];
            const buttons = {
                items: document.getElementById('showItems'),
                attacks: document.getElementById('showAttacks'),
                materials: document.getElementById('showMaterials')
            };
            tabs.forEach(id => {
                document.getElementById(id).classList.toggle('hidden', id !== tabId);
                buttons[id].classList.remove('bg-accent', 'text-secondary');
                buttons[id].classList.add('bg-secondary', 'text-text');
            });
            buttons[tabId].classList.add('bg-accent', 'text-secondary');
            buttons[tabId].classList.remove('bg-secondary', 'text-text');
        }

        function updateItemSections() {
            const itemSelectionSection = document.getElementById('item-selection');
            const statusSection = document.getElementById('status-text-item');
            itemSelectionSection.classList.add('hidden');
            statusSection.classList.add('hidden');
            if (activeUserMonster.hunger != 4 && activeUserMonster.sleep_time == null && activeUserMonster.monster.stage != "Egg") {
                itemSelectionSection.classList.remove('hidden');
            } else {
                statusSection.textContent = activeUserMonster.sleep_time ? 'Monster is sleeping' : 'Monster is full';
                if (activeUserMonster.monster.stage == "Egg") {
                    statusSection.textContent = "Egg can not do this.";
                }
                statusSection.classList.remove('hidden');
            }
        }

        function highlightEquippedAttack() {
            document.querySelectorAll(".attack-div").forEach(container => {
                const attackText = container.querySelector("p");
                container.classList.remove("bg-accent", "bg-secondary");
                attackText.classList.remove("text-secondary", "text-text");
                const userAttack = JSON.parse(container.querySelector("button").getAttribute("data-item"));

                if (activeUserMonster.attack === userAttack.id) {
                    container.classList.add("bg-accent");
                    attackText.classList.add("text-text");
                } else {
                    container.classList.add("bg-secondary");
                    attackText.classList.add("text-secondary");
                }
            });
        }

        function closeStatMenu() {
            statsPanel.classList.add('hidden');
            container.classList.remove('rounded-b-none');
            if (activeUserMonster) {
                activeUserMonster.monsterDiv.classList.remove('clicked');
            }
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
            userMonster.tooltip = tooltip;

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
                    if (this.sleep_time == null && this.energy > 0 && this.monster.stage != "Egg") {
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

        document.getElementById('showItems').addEventListener('click', () => showTab('items'));
        document.getElementById('showAttacks').addEventListener('click', () => showTab('attacks'));
        document.getElementById('showMaterials').addEventListener('click', () => showTab('materials'));

        document.getElementById('close-stats').addEventListener('click', () => {
            closeStatMenu();
        });

        document.querySelectorAll('.openTraining').forEach(button => {
            button.addEventListener('click', function() {
                const trainingSection = document.getElementById('training-section');
                const sleepSection = document.getElementById('sleep-section');
                trainingSection.classList.add('hidden');
                sleepSection.classList.add('hidden');

                if (activeUserMonster.sleep_time == null) {
                    trainingSection.classList.remove('hidden');
                    userEquipment = JSON.parse(this.getAttribute('data-equipment'));
                    setTrainingButton();
                    document.getElementById('equipment-name').textContent = `${userEquipment.equipment.stat} Lvl: ${userEquipment.level}`;
                    monsterImage = document.getElementById('monster-sprite');
                    secondaryImage = document.getElementById('equipment-sprite');
                    secondaryImage.style.backgroundImage = `url(/storage/${userEquipment.equipment.image})`;
                    clearInterval(secondaryAnimationInterval);
                    secondaryImage.style.backgroundPositionX = `-${0 * 48}px`;
                    monsterImage.style.backgroundImage = getMonsterImage(activeUserMonster);
                    monsterAnimationInterval = animateSprite(monsterImage, [1, 2], 400, monsterAnimationInterval);
                } else {
                    sleepSection.classList.remove('hidden');
                }
            });
        });

        document.getElementById('openItems').addEventListener('click', function() {
            highlightEquippedAttack();
            updateItemSections();
        });

        document.querySelectorAll('.useItem').forEach(button => {
            button.addEventListener('click', function() {
                const itemSelectionSection = document.getElementById('item-selection');
                itemSelectionSection.classList.add('hidden');

                const animationSection = document.getElementById('item-usage-section');
                animationSection.classList.remove('hidden');

                userItem = JSON.parse(this.getAttribute('data-item'));
                monsterImage = document.getElementById('monster-item-sprite');
                secondaryImage = document.getElementById('item-sprite');
                secondaryImage.style.backgroundImage = `url(/storage/${userItem.item.image})`;
                monsterImage.style.backgroundImage = getMonsterImage(activeUserMonster);
                monsterAnimationInterval = animateSprite(monsterImage, [1, 2], 400, monsterAnimationInterval);
                secondaryAnimationInterval = animateSprite(secondaryImage, [0, 1, 2, 3], 800, secondaryAnimationInterval);

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
                        result.userItemQuantity == 0 ? this.closest('.flex.flex-col.items-center').remove() : this.nextElementSibling.textContent = result.userItemQuantity;
                        setTimeout(() => {
                            itemSelectionSection.classList.remove('hidden');
                            animationSection.classList.add('hidden');
                            updateItemSections();
                            updateStats();
                        }, 3600);
                    });
            });
        });

        document.querySelectorAll(".useAttack").forEach(button => {
            button.addEventListener("click", function() {
                const userAttack = JSON.parse(this.getAttribute("data-item"));
                const data = {
                    user_attack_id: userAttack.id,
                    user_monster_id: activeUserMonster.id
                };

                activeUserMonster.attack = userAttack.id;
                highlightEquippedAttack();

                fetch("{{ route('monster.attack') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content")
                    },
                    body: JSON.stringify(data)
                });
            });
        });

        document.querySelectorAll(".useBackground").forEach(button => {
            button.addEventListener("click", function() {
                const userBackground = JSON.parse(button.getAttribute("data-background"));
                const equipped_id = JSON.parse(button.getAttribute("data-equipped_id"));
                if (equipped_id != userBackground.id) {
                    document.getElementById("loading-section-background").classList.remove("hidden");
                    document.getElementById("backgrounds").classList.add("hidden");
                    const data = {
                        user_background_id: userBackground.id,
                    };

                    fetch("{{ route('digigarden.background') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content")
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.successful == true) {
                                window.location.reload();
                            }
                        })
                }
            });
        });

        document.getElementById('trainingButton').addEventListener('click', function() {
            if (!training) {
                const trainingButton = document.getElementById('trainingButton');
                const labelSpan = trainingButton.querySelector('.label');
                const icon = trainingButton.querySelector('i.icon');
                labelSpan.textContent = 'Stop';
                icon.className = 'icon fa fa-stop';

                progress = 0;
                direction = 1;

                monsterAnimationInterval = animateSprite(monsterImage, [3, 4], 400, monsterAnimationInterval);
                secondaryAnimationInterval = animateSprite(secondaryImage, [0, 1, 2, 3], 400, secondaryAnimationInterval);

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

                monsterAnimationInterval = animateSprite(monsterImage, trainingFrames, 400, monsterAnimationInterval);

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
                    if (result.userMonster.sleep_time) {
                        closeStatMenu();
                    }
                });
        });

        document.getElementById('evolutionButton').addEventListener('click', function() {
            const data = {
                user_monster_id: activeUserMonster.id
            };
            this.classList.add("hidden");
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
                        spriteDiv.classList.remove('flicker');
                        spriteDiv.style.backgroundImage = getMonsterImage(activeUserMonster);
                        updateStats();
                        setTimeout(() => {
                            spriteDiv.style.transition = "opacity 0.5s";
                            spriteDiv.style.opacity = 1;
                            currentMonsterDiv.classList.remove("evolution-animation");
                        }, 50);
                    }, 1500);
                });
        });

        editIcon.addEventListener('click', function() {
            nameInput.value = nameDisplay.textContent;
            nameDisplay.classList.add('hidden');
            nameInput.classList.remove('hidden');
            editIcon.classList.add('hidden');
            saveBtn.classList.remove('hidden');
        });

        saveBtn.addEventListener('click', function() {
            nameDisplay.classList.remove('hidden');
            nameInput.classList.add('hidden');
            editIcon.classList.remove('hidden');
            saveBtn.classList.add('hidden');
            const newName = nameInput.value;
            const data = {
                user_monster_id: activeUserMonster.id,
                name: nameInput.value
            };
            fetch("{{ route('monster.name') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .then(result => {
                    if (result.successful) {
                        activeUserMonster.name = result.newName;
                        activeUserMonster.tooltip.innerText = result.newName;
                        document.querySelector('#stat-name span').textContent = result.newName;
                    }
                });
        });
    });
</script>