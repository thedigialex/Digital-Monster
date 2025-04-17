<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Adventure
        </x-fonts.sub-header>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                {{ $currentLocation->location->name }}
            </x-fonts.sub-header>

            <x-container.modal name="user-items" title="Locations">
                <x-slot name="button">
                    <x-buttons.primary id="openMenu" label="Locations" icon="fa-location-dot" @click="open = true" />
                </x-slot>
                <div class="flex gap-4 p-2 rounded-t-md bg-secondary w-full justify-center">
                    <button id="showLocations" class="bg-accent text-secondary px-4 py-2 rounded-md">Locations</button>
                </div>
                <div class="flex flex-col justify-center items-center bg-cover bg-center rounded-b-md"
                    style="background-image: url('{{ asset($background) }}'); height: 30vh;">
                    <x-alerts.spinner id="loading-section-location"></x-alerts.spinner>
                    <div id="locations" class="flex flex-wrap justify-center items-center gap-4 overflow-y-auto">
                        @foreach ($userLocations as $userLocation)
                        <div class="location-div flex flex-col items-center w-36 p-2 bg-secondary border-2 border-accent rounded-md"
                            data-location-id="{{ $userLocation->id }}">
                            <div class="w-24 h-24 p-2 rounded-md bg-primary">
                                <button class="userLocation w-full h-full"
                                    data-location='{{ json_encode($userLocation) }}'
                                    style="background: url('/storage/{{ $userLocation->location->image }}') no-repeat; background-size: cover; background-position: 0 0;">
                                </button>
                            </div>
                            <x-fonts.paragraph class="background-p text-text"> {{ $userLocation->location->name }}</x-fonts.paragraph>
                        </div>
                        @endforeach
                    </div>
                </div>
            </x-container.modal>
        </x-slot>
        <x-container.background id="setup-section" :background="$background" class="rounded-b-md">
            @if ($userMonsters->isEmpty())
            <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">No Monsters Available To Explore</x-fonts.paragraph>
            @else

            <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">Select a monster</x-fonts.paragraph>
            <div class="flex items-center gap-4 pt-4">
                <x-buttons.arrow direction="left" id="scrollLeft" class="hidden"></x-buttons.arrow>
                <div id="monsterCarousel" class="flex items-center gap-4" data-monsters='@json($userMonsters)'>
                </div>
                <x-buttons.arrow direction="right" id="scrollRight" class="hidden"></x-buttons.arrow>
            </div>

            <div id="confirm-section" class="hidden flex justify-center  items-center gap-4 flex-col pt-4">
                <x-buttons.square id="selectMonsterButton" text="Adventure" icon="fa-map" />
            </div>
            @endif
        </x-container.background>
        <x-container.background id="adventure-section" class="hidden rounded-b-md gap-4" :background="$background">
            <x-buttons.primary id="backButton" icon="fa-repeat" label="Switch" />
            <div id="movementArea" class="relative w-full md:w-1/4 h-32 overflow-hidden">
                <div id="movingSpriteWrapper" class="absolute left-0">
                    <x-container.sprite id="user-monster-sprite" :rotate="true" />
                </div>
            </div>
            <x-fonts.paragraph id="messageBox" class="text-text p-2 bg-primary rounded-md">Adventure Forth!</x-fonts.paragraph>
            <x-buttons.square id="stepButton" text="Step" icon="fa-forward" />
        </x-container.background>
    </x-container>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById("monsterCarousel");
        const confirmSection = document.getElementById("confirm-section");
        const battleSection = document.getElementById("adventure-section");
        const loadingSection = document.getElementById("loading-section");
        const battleArena = document.getElementById("battle-arena");
        const setupSection = document.getElementById("setup-section");
        const scrollLeft = document.getElementById("scrollLeft");
        const scrollRight = document.getElementById("scrollRight");
        const movingMonster = document.getElementById('user-monster-sprite');
        const stepButton = document.getElementById("stepButton");
        const userMonsters = JSON.parse(carousel.getAttribute("data-monsters"));

        let itemsPerPage = window.innerWidth <= 640 ? 2 : 4;
        let currentIndex = 0;
        let activeUserMonster;
        let monsterElements = [];
        let monsterAnimationInterval;

        function generateMonsters() {
            userMonsters.forEach(userMonster => {
                const monsterDiv = document.createElement("div");
                monsterDiv.classList.add(
                    "flex", "flex-col", "items-center", "w-36", "p-2",
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
                    confirmSection.classList.remove("hidden");
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

            scrollLeft.style.visibility = currentIndex === 0 ? "hidden" : "visible";
            scrollRight.style.visibility = currentIndex + itemsPerPage >= monsterElements.length ? "hidden" : "visible";
        }

        function userMonsterAnimation(frames) {
            let frameIndex = 0;
            movingMonster.style.backgroundImage = getMonsterImage(activeUserMonster);
            clearInterval(monsterAnimationInterval);
            monsterAnimationInterval = setInterval(() => {
                monsterIndex = frames[frameIndex];
                frameIndex = (frameIndex + 1) % frames.length;
                movingMonster.style.backgroundPositionX = `-${monsterIndex * 48}px`;
            }, 400);
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

        scrollLeft.addEventListener("click", function() {
            if (currentIndex > 0) {
                currentIndex -= itemsPerPage;
                renderMonsters();
            }
        });

        scrollRight.addEventListener("click", function() {
            if (currentIndex + itemsPerPage < monsterElements.length) {
                currentIndex += itemsPerPage;
                renderMonsters();
            }
        });

        stepButton.addEventListener("click", function() {
            stepButton.disabled = true;
            document.getElementById("backButton").classList.add("hidden");
            const data = {
                user_monster_id: activeUserMonster.id
            };
            fetch("{{ route('adventure.step') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .then(result => {
                    if (result.successful) {
                        const maxMove = movementArea.clientWidth - movingSpriteWrapper.clientWidth;
                        movingSpriteWrapper.style.transition = `transform ${result.duration / 1000}s linear`;
                        movingSpriteWrapper.style.transform = `translateX(${maxMove}px)`;
                        setTimeout(() => {
                            movingSpriteWrapper.style.transition = 'none';
                            movingSpriteWrapper.style.transform = 'translateX(0)';
                            stepButton.disabled = false;
                            document.getElementById('messageBox').textContent = result.message;
                            document.getElementById("backButton").classList.remove("hidden");
                        }, result.duration);
                    }
                });
        });

        document.getElementById("backButton").addEventListener("click", function() {
            battleSection.classList.add("hidden");
            setupSection.classList.remove("hidden");
            confirmSection.classList.remove("hidden");
            document.getElementById('messageBox').textContent = "Adventure Forth!";
        });

        document.getElementById("selectMonsterButton").addEventListener("click", function() {
            userMonsterAnimation([0, 1]);
            setupSection.classList.add("hidden");
            confirmSection.classList.add("hidden");
            battleSection.classList.remove("hidden");
        });

        document.querySelectorAll(".userLocation").forEach(button => {
            button.addEventListener("click", function() {
                const userLocation = JSON.parse(button.getAttribute("data-location"));
                document.getElementById("loading-section-location").classList.remove("hidden");
                document.getElementById("locations").classList.add("hidden");
                const data = {
                    user_location_id: userLocation.id,
                };

                fetch("{{ route('adventure.location') }}", {
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
            });
        });

        generateMonsters();
        renderMonsters();
    });
</script>