<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            DigiConverge
        </x-fonts.sub-header>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                DigiConverge
            </x-fonts.sub-header>
            <x-fonts.paragraph id="user-balance">
                DataCrystals <span>{{ $count}} / 10</span>
            </x-fonts.paragraph>
        </x-slot>
        <x-container.background id="setup-section" :background="$background">
            <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">Select an egg</x-fonts.paragraph>
            <div class="flex items-center gap-4 pt-4">
                <x-buttons.arrow direction="left" id="scrollLeft"></x-buttons.arrow>
                <div id="monsterCarousel" class="flex items-center gap-4" data-eggs='@json($eggs)'>
                </div>
                <x-buttons.arrow direction="right" id="scrollRight"></x-buttons.arrow>
            </div>
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById("monsterCarousel");

        const eggMonsters = JSON.parse(carousel.getAttribute("data-eggs"));
        let itemsPerPage = window.innerWidth <= 768 ? 1 : 5;
        let currentIndex = 0;
        let monsterElements = [];


        function generateMonsters() {
            eggMonsters.forEach(monster => {
                const monsterDiv = document.createElement("div");
                monsterDiv.classList.add(
                    "flex", "flex-col", "items-center", "w-32", "p-2",
                    "bg-secondary", "border-2", "border-accent", "rounded-md",
                    "cursor-pointer", "text-text"
                );
                monsterDiv.id = `monster-${monster.id}`;

                monsterDiv.addEventListener("click", function() {
                    //if (activeUserMonster) {
                    //    const previousActiveMonsterDiv = document.querySelector(`#monster-${activeUserMonster.id}`);
                    //    if (previousActiveMonsterDiv) {
                    //        previousActiveMonsterDiv.classList.remove("bg-accent", "text-secondary");
                    //        previousActiveMonsterDiv.classList.add("text-text", "bg-secondary");
                    //    }
                    //}
                    //typeSection.classList.remove("hidden");
                    //activeUserMonster = userMonster;
                    //monsterDiv.classList.remove("bg-secondary", "text-text");
                    //monsterDiv.classList.add("text-secondary", "bg-accent");
                });

                const imgDiv = document.createElement("div");
                imgDiv.classList.add("w-24", "h-24", "p-2", "rounded-md", "bg-primary");

                const img = document.createElement("img");
                img.classList.add("w-full", "h-full", "object-cover");
                img.style.objectPosition = "0 0";

                img.src = `/storage/${monster.image_0}`;

                imgDiv.appendChild(img);

                const nameParagraph = document.createElement("p");
                nameParagraph.classList.add("text-center");
                nameParagraph.textContent = monster.name;

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

        document.getElementById("scrollLeft").addEventListener("click", function() {
            currentIndex = Math.max(0, currentIndex - itemsPerPage);
            renderMonsters();
        });

        document.getElementById("scrollRight").addEventListener("click", function() {
            if (currentIndex + itemsPerPage < eggMonsters.length) {
                currentIndex += itemsPerPage;
            } else {
                currentIndex = eggMonsters.length - (eggMonsters.length % itemsPerPage || itemsPerPage);
            }

            renderMonsters();
        });

        generateMonsters();
        renderMonsters();
    });
</script>