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
            <div>
                <x-buttons.button type="edit" label="Switch" icon="fa-repeat" id="backButton" class="hidden" />
                <x-container.modal name="user-items" title="Locations">
                    <x-slot name="button">
                        <x-buttons.button type="edit" label="Locations" icon="fa-location-dot" @click="open = true" id="openMenu" />
                    </x-slot>
                    <x-container.tab-div class="pt-2 px-2 justify-center bg-secondary">
                        <x-buttons.tab id="showLocations" class="bg-accent text-secondary p-2" label="Locations" />
                    </x-container.tab-div>
                    <div class="flex flex-col justify-center items-center bg-cover bg-center rounded-b-md"
                        style="background-image: url('{{ asset($background) }}'); height: 40vh;">
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
            </div>
        </x-slot>
        <x-container.background :background="$background" class="rounded-b-md gap-4">
            <div id="setup-section" class="flex flex-col gap-4 w-full">
                <div id="monster-section" class="flex flex-col items-center gap-4 w-full">
                    @if(!$userMonsters->isEmpty())
                    <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">Select a monster for adventure</x-fonts.paragraph>
                    <div class="flex justify-center items-center gap-2 w-full">
                        <x-buttons.button type="edit" id="scrollLeft" label="" icon="fa-chevron-left" />
                        <div id="monsterScroll" class="flex transition-transform duration-300 w-3/4 lg:w-1/3 gap-4 overflow-hidden bg-primary p-4 rounded-md">
                            @foreach ($userMonsters as $userMonster)
                            <x-container.monster-card :data-monster="$userMonster" :id="'monster-' . $userMonster->id" buttonClass="userMonster" divClass="monster-div" />
                            @endforeach
                        </div>
                        <x-buttons.button type="edit" id="scrollRight" label="" icon="fa-chevron-right" />
                    </div>
                </div>
                @else
                <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">No monsters are able to adventure</x-fonts.paragraph>
                @endif
            </div>
            <div id="adventure-section" class="hidden flex flex-col gap-4 w-full items-center">
                <div id="movementArea" class="lg:w-1/2 w-full relative overflow-hidden h-16">
                    <div id="movingSpriteWrapper" class="absolute top-0 left-0">
                        <x-container.sprite id="user-monster-sprite" :rotate="true" />
                    </div>
                </div>
                <x-fonts.paragraph id="messageBox" class="text-text p-4 bg-primary rounded-md">Adventure Forth!</x-fonts.paragraph>
                <x-buttons.button type="edit" id="stepButton" label="Step" icon="fa-forward" />
            </div>
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monsterSection = document.getElementById("monster-section");
        const switchButton = document.getElementById("backButton");
        const locationButton = document.getElementById("openMenu");
        const scrollContainer = document.getElementById('monsterScroll');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');
        const battleSection = document.getElementById("adventure-section");
        const setupSection = document.getElementById("setup-section");
        const movingMonster = document.getElementById('user-monster-sprite');
        const stepButton = document.getElementById("stepButton");

        let activeUserMonster;
        let monsterElements = [];
        let monsterAnimationInterval;

        scrollLeftBtn?.addEventListener('click', () => scrollCards(-1));
        scrollRightBtn?.addEventListener('click', () => scrollCards(1));
        switchButton.addEventListener('click', () => toggleSections());

        stepButton.addEventListener("click", function() {
            stepButton.disabled = true;
            stepButton.classList.add("hidden");
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
                            stepButton.classList.remove("hidden");
                        }, result.duration);
                    }
                });
        });

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
            switchButton.classList.add("hidden");
            battleSection.classList.add("hidden");
            monsterSection.classList.remove("hidden");
            setupSection.classList.remove("hidden");
            locationButton.classList.remove("hidden");
            document.getElementById('messageBox').textContent = "Adventure Forth!";
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
                "Vaccine": userMonster.monster.image_1,
                "Virus": userMonster.monster.image_2
            };
            return `url(/storage/${imageMap[userMonster.type] || userMonster.monster.image_0})`;
        }

        document.querySelectorAll('.userMonster').forEach(button => {
            button.addEventListener('click', function() {
                activeUserMonster = JSON.parse(this.getAttribute("data-monster"));
                highlightUserMonster();
                userMonsterAnimation([0, 1]);
                switchButton.classList.remove("hidden");
                monsterSection.classList.add("hidden");
                battleSection.classList.remove("hidden");
                locationButton.classList.add("hidden");
            });
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
    });
</script>