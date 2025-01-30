<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                {{ __('Dashboard') }}
            </x-fonts.sub-header>
        </div>
    </x-slot>

    <x-container>
        <div
            id="monster-container"
            class="relative w-full h-[500px] overflow-hidden rounded-lg shadow-lg"
            data-monsters='@json($userMonsters)'
            style="background-image: url('/images/background-dashboard.png'); background-size: cover; background-position: center;">
        </div>
    </x-container>

    <script>
    const container = document.getElementById('monster-container');
    const userMonsters = JSON.parse(container.getAttribute('data-monsters'));
    const screenWidth = container.offsetWidth;
    const screenHeight = container.offsetHeight;

    userMonsters.forEach(monster => {
        // Monster container
        const monsterDiv = document.createElement('div');
        monsterDiv.className = 'monster';
        monsterDiv.style.width = '48px';
        monsterDiv.style.height = '48px';
        monsterDiv.style.position = 'absolute';

        // Inner sprite element for flipping
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

        // Tooltip
        const tooltip = document.createElement('span');
        tooltip.className = 'tooltip';
        tooltip.innerText = monster.name;

        monsterDiv.appendChild(spriteDiv);
        monsterDiv.appendChild(tooltip);
        container.appendChild(monsterDiv);

        // Sprite animation with desynchronization
        let frame = Math.floor(Math.random() * 3); // Random starting frame
        const animationInterval = 300 + Math.random() * 300; // Random interval between 300ms to 600ms
        setInterval(() => {
            frame = (frame + 1) % 3;
            spriteDiv.style.backgroundPositionX = `-${frame * 48}px`;
        }, animationInterval);

        let previousX = parseFloat(monsterDiv.style.left) || 0;

        // Initial random position
        const x = Math.random() * (screenWidth - 48);
        const y = Math.random() * (screenHeight - 48);
        monsterDiv.style.left = `${x}px`;
        monsterDiv.style.top = `${y}px`;

        const movementInterval = 4000 + Math.random() * 2000; // Random movement interval between 4s and 6s

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

            // Flip sprite without affecting the tooltip
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
    `;
    document.head.appendChild(style);
</script>

</x-app-layout>