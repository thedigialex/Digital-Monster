<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Colosseum
        </x-fonts.sub-header>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                Colosseum
            </x-fonts.sub-header>
            <x-buttons.button type="edit" label="Switch" icon="fa-repeat" id="backButton" class="hidden" />
        </x-slot>
        <x-container.background id="setup-section" :background="$background" class="rounded-b-md gap-4">
            <div id="monster-section" class="flex flex-col items-center gap-4 w-full">
                @if(!$userMonsters->isEmpty())
                <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">Select a monster for battle</x-fonts.paragraph>
                <div id="monsterScrollWrapper" class="flex justify-center items-center gap-2 w-full">
                    <x-buttons.button type="edit" id="scrollLeft" label="" icon="fa-chevron-left" />
                    <div id="monsterScroll" class="flex transition-transform duration-300 w-3/4 lg:w-1/3 gap-4 overflow-hidden bg-primary p-4 rounded-md">
                        @foreach ($userMonsters as $userMonster)
                        <x-container.user-monster-card :data-monster="$userMonster" :id="'monster-' . $userMonster->id" buttonClass="userMonster" divClass="monster-div" />
                        @endforeach
                    </div>
                    <x-buttons.button type="edit" id="scrollRight" label="" icon="fa-chevron-right" />
                </div>
            </div>
            <div id="type-section" class="hidden flex items-center gap-4 flex-col pt-4">
                <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">Player OR Wild</x-fonts.paragraph>
                <div class="flex justify-center items-center gap-4 ">
                    <x-buttons.button type="edit" id="userBattleButton" label="Player" icon="fa-user" />
                    <x-buttons.button type="edit" id="wildBattleButton" label="Wild" icon="fa-robot" />
                </div>
            </div>
            @else
            <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">No monsters are able to battle</x-fonts.paragraph>
            @endif
        </x-container.background>

        <x-container.background id="battle-section" :background="$background" class="hidden rounded-b-md">
            <x-alerts.spinner id="loading-section"></x-alerts.spinner>
            <div id="battle-arena" class="flex justify-around items-center gap-4 w-full md:w-1/2">
                <x-container.sprite id="enemy-monster-sprite" :rotate="true" name='test'></x-container.sprite>
                <img id="attack-image" class="absolute w-6 h-6 hidden" src="" alt="Attack Image">
                <x-container.sprite id="user-monster-sprite"></x-container.sprite>
            </div>
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const typeSection = document.getElementById("type-section");
        const monsterSection = document.getElementById("monster-section");
        const battleSection = document.getElementById("battle-section");
        const loadingSection = document.getElementById("loading-section");
        const switchButton = document.getElementById("backButton");
        const battleArena = document.getElementById("battle-arena");
        const setupSection = document.getElementById("setup-section");
        const scrollContainer = document.getElementById('monsterScroll');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');

        scrollLeftBtn?.addEventListener('click', () => scrollCards(-1));
        scrollRightBtn?.addEventListener('click', () => scrollCards(1));
        switchButton.addEventListener('click', () => toggleSections());

        let monsterElements = [];
        let monsterImage;
        let monsterAnimationInterval;
        let enemyMonsterImage;
        let enemyMonsterAnimationInterval;
        let enemyMonster;
        let activeUserMonster;

        function getCardWidth() {
            const firstCard = scrollContainer.querySelector('div');
            return firstCard ? firstCard.offsetWidth + 16 : 0;
        }

        function scrollCards(direction) {
            const cardWidth = getCardWidth();
            scrollContainer.scrollBy({
                left: direction * cardWidth,
                behavior: 'smooth'
            });
        }

        function highlightUserMonster() {
            document.querySelectorAll(".monster-div").forEach(container => {
                const monsterText = container.querySelector("p");
                container.classList.remove("bg-accent", "bg-secondary");
                monsterText.classList.remove("text-secondary", "text-text");
                const userMonster = JSON.parse(container.querySelector("button").getAttribute("data-monster"));
                if (activeUserMonster.id === userMonster.id) {
                    container.classList.add("bg-accent");
                    monsterText.classList.add("text-text");
                } else {
                    container.classList.add("bg-secondary");
                    monsterText.classList.add("text-secondary");
                }
            });
        }

        function toggleSections() {
            typeSection.classList.add("hidden");
            switchButton.classList.add("hidden");
            monsterSection.classList.remove("hidden");
        }

        function startBattle(animationFrame, removeUserMonster) {
            switchButton.classList.add("hidden");
            const attackImage = document.getElementById('attack-image');
            monsterImage = document.getElementById('user-monster-sprite');
            enemyMonsterImage = document.getElementById('enemy-monster-sprite');

            userMonsterAnimation([3, 4]);
            enemyMonsterAnimation([3, 4]);

            let attackImages = {
                leftToRight: activeUserMonster.attack.item.image,
                rightToLeft: enemyUserMonster.attack.image
            };

            let attackCount = 0;

            function getPositions() {
                const userRect = monsterImage.getBoundingClientRect();
                const enemyRect = enemyMonsterImage.getBoundingClientRect();
                return {
                    userX: userRect.right - userRect.width,
                    enemyX: enemyRect.right - enemyRect.width
                };
            }

            function performAttack() {
                if (attackCount >= 6) {
                    attackImage.classList.add('hidden');
                    const userFrame = animationFrame[0] + animationFrame[1] + animationFrame[2] >= 2 ? [0, 8] : [0, 7];
                    const enemyFrame = animationFrame[0] + animationFrame[1] + animationFrame[2] >= 2 ? [0, 7] : [0, 8];

                    userMonsterAnimation(userFrame);
                    enemyMonsterAnimation(enemyFrame);
                    setTimeout(() => {
                        const monsterDiv = document.querySelector(`#monster-${activeUserMonster.id}`);
                        switchButton.classList.remove("hidden");
                        if (removeUserMonster) {
                            monsterDiv.remove();
                            activeUserMonster = null;
                            toggleSections();
                        }
                        battleSection.classList.add("hidden");
                        setupSection.classList.remove("hidden");
                    }, 5000);
                    return;
                }

                let {
                    userX,
                    enemyX
                } = getPositions();
                let fromLeft = attackCount % 2 == 0;

                attackImage.src = "/storage/" + (fromLeft ? attackImages.leftToRight : attackImages.rightToLeft);
                attackImage.classList.remove('hidden');
                attackImage.style.position = "absolute";
                attackImage.style.left = `${fromLeft ? userX : enemyX}px`;
                attackImage.style.transform = fromLeft ? "scaleX(1)" : "scaleX(-1)";

                let targetX = fromLeft ? enemyX : userX;

                attackImage.animate([{
                        transform: `translateX(0px) ${fromLeft ? "scaleX(1)" : "scaleX(-1)"}`
                    },
                    {
                        transform: `translateX(${targetX - (fromLeft ? userX : enemyX)}px) ${fromLeft ? "scaleX(1)" : "scaleX(-1)"}`
                    }
                ], {
                    duration: 1000,
                    easing: "linear",
                    fill: "forwards"
                });

                setTimeout(() => {
                    attackImage.classList.add('hidden');
                    if (fromLeft) {
                        playDamageAnimation(enemyMonsterImage);
                    } else {
                        playDamageAnimation(monsterImage);
                    }

                    attackCount++;
                    setTimeout(performAttack, 1000);
                }, 1000);
            }

            performAttack();
        }

        function playDamageAnimation(monster) {
            let originalTransform = monster.style.transform || "";

            monster.style.transition = "transform 0.1s ease-in-out";
            monster.style.transform = originalTransform + " scale(1.1)";

            setTimeout(() => {
                monster.style.transform = originalTransform;
            }, 250);
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

        function userMonsterAnimation(frames) {
            let frameIndex = 0;
            monsterImage.style.backgroundImage = getMonsterImage(activeUserMonster);
            clearInterval(monsterAnimationInterval);
            monsterAnimationInterval = setInterval(() => {
                monsterIndex = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                monsterImage.style.backgroundPositionX = `-${monsterIndex * 48}px`;
            }, 400);
        }

        function enemyMonsterAnimation(frames) {
            let frameIndex = 0;
            enemyMonsterImage.style.backgroundImage = getMonsterImage(enemyUserMonster);
            clearInterval(enemyMonsterAnimationInterval);
            enemyMonsterAnimationInterval = setInterval(() => {
                monsterIndex = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                enemyMonsterImage.style.backgroundPositionX = `-${monsterIndex * 48}px`;
            }, 400);
        }

        document.querySelectorAll('.userMonster').forEach(button => {
            button.addEventListener('click', function() {
                activeUserMonster = JSON.parse(this.getAttribute("data-monster"));
                highlightUserMonster();
                typeSection.classList.remove("hidden");
                switchButton.classList.remove("hidden");
                monsterSection.classList.add("hidden");
            });
        });

        document.getElementById("wildBattleButton")?.addEventListener("click", function() {
            battleSection.classList.remove("hidden");
            loadingSection.classList.remove("hidden");
            setupSection.classList.add("hidden");
            battleArena.classList.add("hidden");

            const data = {
                user_monster_id: activeUserMonster.id
            };
            fetch("{{ route('colosseum.generateBattle') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .then(result => {
                    if (result.successful) {
                        loadingSection.classList.add("hidden");
                        battleArena.classList.remove("hidden");
                        enemyUserMonster = result.enemyUserMonster;
                        startBattle(result.animationFrame, result.removeUserMonster);
                        monsterName = document.getElementById('user-monster-sprite-name');
                        monsterName.textContent = activeUserMonster.name;
                        enemyMonsterName = document.getElementById('enemy-monster-sprite-name');
                        enemyMonsterName.textContent = enemyUserMonster.name;
                    } else {
                        //reset battle thing and show a message.
                    }
                });
        });
    });
</script>