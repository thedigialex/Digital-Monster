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
            const monsterDiv = document.createElement('div');
            monsterDiv.className = 'monster';
            monsterDiv.style.width = '48px';
            monsterDiv.style.height = '48px';
            monsterDiv.style.position = 'absolute';
            monsterDiv.style.backgroundImage = `url(/storage/${monster.digital_monster.sprite_image_0})`;
            monsterDiv.style.backgroundSize = '480px 48px';
            const x = Math.random() * (screenWidth - 48);
            const y = Math.random() * (screenHeight - 48);
            monsterDiv.style.left = `${x}px`;
            monsterDiv.style.top = `${y}px`;
            container.appendChild(monsterDiv);
            let frame = 0;
            setInterval(() => {
                frame = (frame + 1) % 3;
                monsterDiv.style.backgroundPositionX = `-${frame * 48}px`;
            }, 450);
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
                monsterDiv.style.transition = 'left 2s, top 2s';
                monsterDiv.style.left = `${newX}px`;
                monsterDiv.style.top = `${newY}px`;
            }, 5000);
        });
    </script>
</x-app-layout>