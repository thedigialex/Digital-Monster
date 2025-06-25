<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ $user->name }}'s DigiGarden
        </x-fonts.sub-header>
        <a href="{{ route('users.index') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                {{ $user->name }}'s DigiGarden
            </x-fonts.sub-header>
        </x-slot>
        <div class="relative w-full rounded-b-md shadow-lg h-[60vh] overflow-hidden">
            <div
                id="monster-container"
                class="w-full h-full bg-cover bg-center"
                data-monsters='@json($userMonsters)'
                style="background-image: url('{{ asset($background) }}');"></div>
            <div class="absolute inset-0 pointer-events-none {{ $timeOfDay }}"></div>
        </div>
    </x-container>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let interval;
        let activeUserMonster;
        let monsterImage;
        let monsterAnimationInterval;

        const container = document.getElementById('monster-container');

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
        });
    });
</script>