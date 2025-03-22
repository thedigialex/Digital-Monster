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
                    Colosseum
                </x-fonts.sub-header>
            </div>
        </x-slot>
        <div id="setup-section">
            <div class="flex flex-col items-center p-4 gap-4">
                <x-fonts.sub-header class="text-center">
                    Select a monster
                </x-fonts.sub-header>
                <div class="gap-4 flex justify-center items-center pb-4">
                    <button id="scrollLeft" class="p-4 bg-secondary text-text hover:text-accent rounded-md">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <div class="flex items-center overflow-hidden gap-4 transition-transform duration-300 ease-in-out" data-monsters='@json($userMonsters)' id="monsterCarousel">
                    </div>
                    <button id="scrollRight" class="p-4 bg-secondary text-text hover:text-accent rounded-md">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div id="type-section" class="hidden flex flex-col items-center p-4 gap-4">
                <x-fonts.sub-header class="text-center">
                    Player OR Wild
                </x-fonts.sub-header>
                <div class="gap-4 flex justify-center items-center pb-4">
                    <x-buttons.square id="userBattleButton" class="w-[150px]"
                        text="Player" />
                    <x-buttons.square id="wildBattleButton" class="w-[150px]"
                        text="Wild" />
                </div>
            </div>
        </div>
        <div id="battle-section" class="hidden flex flex-col justify-center items-center bg-cover bg-center"
            style="background-image: url('/images/background-dashboard.png'); height: 40vh;">
            <div id=loading-section class="flex justify-center items-center h-screen">
                <div class="relative w-24 h-24">
                    <div class="absolute inset-0 border-8 border-transparent border-t-secondary rounded-full animate-spin"></div>
                </div>
            </div>
            <div id="battle-arena" class="flex flex-col justify-center items-center gap-4 p-2 w-full">
                <div class="flex justify-center items-center">
                    <div class="w-16 h-16 p-2">
                        <div id="enemy-monster-sprite" class="w-full h-full"></div>
                    </div>
                    <div class="w-16 h-16 p-2">
                        <div id="user-monster-sprite" class="w-full h-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </x-container>

</x-app-layout>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById("monsterCarousel");
        const scrollLeftBtn = document.getElementById("scrollLeft");
        const scrollRightBtn = document.getElementById("scrollRight");
        const userBattleButton = document.getElementById("userBattleButton");
        const wildBattleButton = document.getElementById("wildBattleButton");
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

        function generateMonsters() {
            userMonsters.forEach(userMonster => {
                const monsterDiv = document.createElement("div");
                monsterDiv.classList.add(
                    "flex", "flex-col", "items-center", "w-28", "p-2",
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
                } else if (userMonster.type === "Virus") {
                    img.src = `/storage/${userMonster.monster.image_1}`;
                } else if (userMonster.type === "Vaccine") {
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

        scrollLeftBtn.addEventListener("click", function() {
            currentIndex = Math.max(0, currentIndex - itemsPerPage);
            renderMonsters();
        });

        scrollRightBtn.addEventListener("click", function() {
            if (currentIndex + itemsPerPage < userMonsters.length) {
                currentIndex += itemsPerPage;
            } else {
                currentIndex = userMonsters.length - (userMonsters.length % itemsPerPage || itemsPerPage);
            }

            renderMonsters();
        });

        generateMonsters();
        renderMonsters();

        let monsterImage;
        let monsterAnimationInterval;
        let enemyMonsterImage;
        let enemyMonsterAnimationInterval;
        let enemyMonster;

        wildBattleButton.addEventListener("click", function() {
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
                        startBattle();
                    }
                    else{
                        //reset battle thing and show a message.
                    }
                });
        });

        function startBattle() {
            monsterImage = document.getElementById('user-monster-sprite');
            enemyMonsterImage = document.getElementById('enemy-monster-sprite');
            userMonsterAnimation([0, 1]);
            enemyMonsterAnimation([0,1]);
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
    });
</script>