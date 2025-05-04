<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            DigiExtract
        </x-fonts.sub-header>
        <a href="{{ route('digiconverge') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                DigiExtract
            </x-fonts.sub-header>
        </x-slot>
        <x-container.background :background="$background" class="rounded-b-md">
            <div id="monster-selection" class="flex flex-col justify-center items-center gap-4">
                <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">{{ $message }}</x-fonts.paragraph>
                <div class="flex items-center gap-4">
                    <x-buttons.arrow direction="left" id="scrollLeft"></x-buttons.arrow>
                    <div id="monsterCarousel" class="flex items-center gap-4" data-monsters='@json($userMonsters)'></div>
                    <x-buttons.arrow direction="right" id="scrollRight"></x-buttons.arrow>
                </div>
            </div>
            <div id="confirm-selection" class="hidden flex flex-col justify-center items-center">
                <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">Extract DataCore from this Monster?</x-fonts.paragraph>
                <div id="single-egg" class="flex items-center gap-4 py-4"></div>
                <div class="flex gap-4">
                    <x-buttons.button type="edit" id="backButton" label="Back" icon="fa-backward" />
                    <x-buttons.button type="edit" id="confirmButton" label="Confirm" icon="fa-check" />
                </div>
            </div>
            <x-alerts.spinner id="loading-section"></x-alerts.spinner>
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById("monsterCarousel");
        const monsterSection = document.getElementById("monster-selection");
        const confirmSelection = document.getElementById("confirm-selection");
        const singleEgg = document.getElementById("single-egg");
        const scrollLeft = document.getElementById("scrollLeft");
        const scrollRight = document.getElementById("scrollRight");
        const userMonsters = JSON.parse(carousel.getAttribute("data-monsters"));

        let currentIndex = 0;
        let selectedMonster;
        const itemsPerPage = window.innerWidth <= 640 ? 1 : 4;
        let monsterElements = [];

        function getMonsterImage(userMonster) {
            if (['Fresh', 'Child'].includes(userMonster.monster.stage)) {
                return `/storage/${userMonster.monster.image_0}`;
            }
            const imageMap = {
                "Vaccine": userMonster.monster.image_1,
                "Virus": userMonster.monster.image_2,
            };
            return `/storage/${imageMap[userMonster.type] || userMonster.monster.image_0}`;
        }

        function renderMonsters() {
            scrollLeft.style.visibility = currentIndex === 0 ? "hidden" : "visible";
            scrollRight.style.visibility = currentIndex + itemsPerPage >= monsterElements.length ? "hidden" : "visible";
            carousel.innerHTML = "";
            const monstersToShow = monsterElements.slice(currentIndex, currentIndex + itemsPerPage);
            monstersToShow.forEach(monster => carousel.appendChild(monster));
        }

        function showConfirmation() {
            const imageSrc = getMonsterImage(selectedMonster);
            monsterSection.classList.add("hidden");
            confirmSelection.classList.remove("hidden");

            singleEgg.innerHTML = `
                <div class="flex flex-col items-center w-36 p-2 bg-secondary border-2 border-accent rounded-md text-text">
                    <div class="w-24 h-24 p-2 rounded-md bg-primary">
                        <img src="${imageSrc}" class="w-full h-full object-cover" style="object-position: 0 0;" />
                    </div>
                    <p class="text-center">${selectedMonster.name}</p>
                </div>
            `;
        }

        function showEggSelection() {
            confirmSelection.classList.add("hidden");
            monsterSection.classList.remove("hidden");
        }

        userMonsters.forEach(userMonster => {
            const monsterDiv = document.createElement("div");
            const imageSrc = getMonsterImage(userMonster);
            monsterDiv.classList.add("flex", "flex-col", "items-center", "w-36", "p-2", "bg-secondary", "border-2", "border-accent", "rounded-md", "cursor-pointer", "text-text");
            monsterDiv.innerHTML = `
                <div class="w-24 h-24 p-2 rounded-md bg-primary">
                    <img src="${imageSrc}" class="w-full h-full object-cover" style="object-position: 0 0;" />
                </div>
                <p class="text-center">${userMonster.name}</p>
            `;

            monsterDiv.addEventListener("click", function() {
                selectedMonster = userMonster;
                showConfirmation();
            });

            monsterElements.push(monsterDiv);
        });

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

        document.getElementById("backButton").addEventListener("click", showEggSelection);

        document.getElementById("confirmButton").addEventListener("click", function() {
            document.getElementById("loading-section").classList.remove("hidden");
            confirmSelection.classList.add("hidden");
            const data = {
                user_monster_id: selectedMonster.id
            };
            fetch("{{ route('extract.monster') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify(data)
                }).then(response => response.json())
                .then(result => {
                    if (result.successful) {
                        window.location.href = '/digigarden';
                    } else {
                        //reset thing and show a message.
                    }
                });
        });

        renderMonsters();
    });
</script>