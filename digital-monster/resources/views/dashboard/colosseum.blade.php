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
        </x-slot>

        <x-container.background id="setup-section" :background="$background">
            <x-container.text>Select a monster</x-container.text>
            <div class="flex items-center gap-4 pt-4">
                <x-buttons.arrow direction="left" id="scrollLeft"></x-buttons.arrow>
                <div id="monsterCarousel" class="flex items-center gap-4" data-monsters='@json($userMonsters)'>
                </div>
                <x-buttons.arrow direction="right" id="scrollRight"></x-buttons.arrow>
            </div>

            <div id="type-section" class="hidden flex items-center gap-4 flex-col pt-4">
                <x-container.text>Player OR Wild</x-container.text>
                <div class="flex justify-center items-center gap-4 ">
                    <x-buttons.square id="userBattleButton" text="Player" />
                    <x-buttons.square id="wildBattleButton" text="Wild" />
                </div>
            </div>
        </x-container.background>

        <x-container.background id="battle-section" :background="$background" class="hidden">
            <x-alerts.spinner id="loading-section"></x-alerts.spinner>
            <div id="battle-arena" class="flex justify-around items-center gap-4 w-full md:w-1/2">
                <x-container.sprite id="enemy-monster-sprite" :rotate="true"></x-container.sprite>
                <img id="attack-image" class="absolute w-6 h-6 hidden" src="" alt="Attack Image">
                <x-container.sprite id="user-monster-sprite"></x-container.sprite>
            </div>
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById("monsterCarousel");
        const typeSection = document.getElementById("type-section");
        const battleSection = document.getElementById("battle-section");
        const loadingSection = document.getElementById("loading-section");
        const battleArena = document.getElementById("battle-arena");
        const setupSection = document.getElementById("setup-section");

        const userMonsters = JSON.parse(carousel.getAttribute("data-monsters"));
        let itemsPerPage = window.innerWidth <= 768 ? 1 : 5;
        let currentIndex = 0;
        let activeUserMonster;
        let monsterElements = [];

        let monsterImage;
        let monsterAnimationInterval;
        let enemyMonsterImage;
        let enemyMonsterAnimationInterval;
        let enemyMonster;

        function generateMonsters() {
            userMonsters.forEach(userMonster => {
                const monsterDiv = document.createElement("div");
                monsterDiv.classList.add(
                    "flex", "flex-col", "items-center", "w-32", "p-2",
                    "bg-secondary", "border-2", "border-accent", "rounded-md",
                    "cursor-pointer", "text-text"
                );
                monsterDiv.id = `monster-${userMonster.id}`;

                monsterDiv.addEventListener("click", function() {
                    if (activeUserMonster) {
                        const previousActiveMonsterDiv = document.querySelector(`#monster-${activeUserMonster.id}`);
                        if (previousActiveMonsterDiv) {
                            previousActiveMonsterDiv.classList.remove("bg-accent", "text-secondary");
                            previousActiveMonsterDiv.classList.add("text-text", "bg-secondary");
                        }
                    }
                    typeSection.classList.remove("hidden");
                    activeUserMonster = userMonster;
                    monsterDiv.classList.remove("bg-secondary", "text-text");
                    monsterDiv.classList.add("text-secondary", "bg-accent");
                });

                const imgDiv = document.createElement("div");
                imgDiv.classList.add("w-24", "h-24", "p-2", "rounded-md", "bg-primary");

                const img = document.createElement("img");
                img.classList.add("w-full", "h-full", "object-cover");
                img.style.objectPosition = "0 0";

                if (["Egg", "Fresh", "Child"].includes(userMonster.monster.stage) || userMonster.type === "Data") {
                    img.src = `/storage/${userMonster.monster.image_0}`;
                } else if (userMonster.type == "Virus") {
                    img.src = `/storage/${userMonster.monster.image_1}`;
                } else if (userMonster.type == "Vaccine") {
                    img.src = `/storage/${userMonster.monster.image_2}`;
                }

                imgDiv.appendChild(img);

                const nameParagraph = document.createElement("p");
                nameParagraph.classList.add("text-center");
                nameParagraph.textContent = userMonster.name;

                monsterDiv.appendChild(imgDiv);
                monsterDiv.appendChild(nameParagraph);

                monsterElements.push(monsterDiv);
            });
        }

        function renderMonsters() {
            carousel.innerHTML = "";
            const monstersToShow = monsterElements.slice(currentIndex, currentIndex + itemsPerPage);
            monstersToShow.forEach(monster => carousel.appendChild(monster));
        }

        function startBattle(animationFrame, removeUserMonster) {

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
                        const activeMonsterDiv = document.querySelector(`#monster-${activeUserMonster.id}`);

                        activeMonsterDiv.classList.remove("text-secondary", "bg-accent");
                        activeMonsterDiv.classList.add("bg-secondary", "text-text");

                        if (removeUserMonster) {
                            activeMonsterDiv.remove();
                        }

                        battleSection.classList.add("hidden");
                        loadingSection.classList.add("hidden");
                        setupSection.classList.remove("hidden");
                        typeSection.classList.add("hidden");
                        activeUserMonster = null;

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
                "Virus": userMonster.monster.image_1,
                "Vaccine": userMonster.monster.image_2
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

        document.getElementById("scrollLeft").addEventListener("click", function() {
            currentIndex = Math.max(0, currentIndex - itemsPerPage);
            renderMonsters();
        });

        document.getElementById("scrollRight").addEventListener("click", function() {
            if (currentIndex + itemsPerPage < userMonsters.length) {
                currentIndex += itemsPerPage;
            } else {
                currentIndex = userMonsters.length - (userMonsters.length % itemsPerPage || itemsPerPage);
            }

            renderMonsters();
        });

        document.getElementById("wildBattleButton").addEventListener("click", function() {
            battleSection.classList.remove("hidden");
            loadingSection.classList.remove("hidden");
            setupSection.classList.add("hidden");
            battleArena.classList.add("hidden");

            const data = {
                user_monster_id: activeUserMonster.id
            };
            fetch("{{ route('monster.generateBattle') }}", {
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
                    } else {
                        //reset battle thing and show a message.
                    }
                });
        });

        generateMonsters();
        renderMonsters();
    });
</script>