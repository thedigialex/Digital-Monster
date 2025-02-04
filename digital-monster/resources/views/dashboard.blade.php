<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Dashboard') }}
            </x-fonts.sub-header>
        </div>
    </x-slot>

    <x-container>
        <!-- Monster Container -->
        <div
            id="monster-container"
            class="relative w-full h-[500px] overflow-hidden rounded-b-lg shadow-lg rounded-t-lg"
            data-monsters='@json($userMonsters)'
            style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
        </div>

        <!-- Stats Panel -->
        <div id="stats-panel" class="hidden bg-neutral w-full p-4 shadow-lg rounded-b-lg">
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

        userMonsters.forEach(monster => {
            const monsterDiv = document.createElement('div');
            monsterDiv.className = 'monster';
            monsterDiv.style.width = '48px';
            monsterDiv.style.height = '48px';
            monsterDiv.style.position = 'absolute';

            const spriteDiv = document.createElement('div');
            spriteDiv.className = 'sprite';
            spriteDiv.style.width = '100%';
            spriteDiv.style.height = '100%';
            spriteDiv.style.backgroundImage = `url(/storage/${monster.digital_monster.sprite_image_0})`;
            spriteDiv.style.backgroundSize = '480px 48px';

            if (monster.digital_monster.stage !== 'Fresh' && monster.digital_monster.stage !== 'Child') {
                switch (monster.type) {
                    case "Vaccine":
                        spriteDiv.style.backgroundImage = `url(/storage/${monster.digital_monster.sprite_image_1})`;
                        break;
                    case "Virus":
                        spriteDiv.style.backgroundImage = `url(/storage/${monster.digital_monster.sprite_image_2})`;
                        break;
                }
            }

            const tooltip = document.createElement('span');
            tooltip.className = 'tooltip';
            tooltip.innerText = monster.name;

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
                statName.textContent = monster.name;
                statStage.textContent = monster.digital_monster.stage;
                statStrength.textContent = `Strength: ${monster.strength}`;
                statAgility.textContent = `Agility: ${monster.agility}`;
                statDefense.textContent = `Defense: ${monster.defense}`;
                statMind.textContent = `Mind: ${monster.mind}`;

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

</x-app-layout>