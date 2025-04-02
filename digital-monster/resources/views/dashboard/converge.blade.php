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
        <x-container.background :background="$background">
            <div id="egg-selection" class="flex flex-col justify-center items-center">
                <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">{{ $message }}</x-fonts.paragraph>
                <div class="flex items-center gap-4 pt-4">
                    <x-buttons.arrow direction="left" id="scrollLeft" class="hidden"></x-buttons.arrow>
                    <div id="monsterCarousel" class="flex items-center gap-4" data-eggs='@json($eggs)'></div>
                    <x-buttons.arrow direction="right" id="scrollRight" class="hidden"></x-buttons.arrow>
                </div>
            </div>
            <div id="confirm-selection" class="hidden flex flex-col justify-center items-center">
                <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">Converge DataCrystals into this egg?</x-fonts.paragraph>
                <div id="single-egg" class="flex items-center gap-4 py-4"></div>
                <div class="flex gap-4">
                    <x-buttons.primary id="backButton" label="Back" icon="fa-backward" />
                    <x-buttons.primary id="confirmButton" label="Confirm" icon="fa-check" />
                </div>
            </div>
            <x-alerts.spinner id="loading-section"></x-alerts.spinner>
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById("monsterCarousel");
        const eggSelection = document.getElementById("egg-selection");
        const confirmSelection = document.getElementById("confirm-selection");
        const singleEgg = document.getElementById("single-egg");
        const scrollLeft = document.getElementById("scrollLeft");
        const scrollRight = document.getElementById("scrollRight");
        const eggMonsters = JSON.parse(carousel.getAttribute("data-eggs"));

        let currentIndex = 0;
        let selectedMonster;
        const itemsPerPage = window.innerWidth <= 640 ? 2 : 4;
        let monsterElements = [];

        function renderMonsters() {
            carousel.innerHTML = "";
            const monstersToShow = monsterElements.slice(currentIndex, currentIndex + itemsPerPage);
            monstersToShow.forEach(monster => carousel.appendChild(monster));

            scrollLeft.style.visibility = currentIndex === 0 ? "hidden" : "visible";
            scrollRight.style.visibility = currentIndex + itemsPerPage >= monsterElements.length ? "hidden" : "visible";
        }

        function showConfirmation() {
            eggSelection.classList.add("hidden");
            confirmSelection.classList.remove("hidden");

            singleEgg.innerHTML = `
                <div class="flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md text-text">
                    <div class="w-24 h-24 p-2 rounded-md bg-primary">
                        <img src="/storage/${selectedMonster.image_0}" class="w-full h-full object-cover" style="object-position: 0 0;" />
                    </div>
                    <p class="text-center">${selectedMonster.name}</p>
                </div>
            `;
        }

        function showEggSelection() {
            confirmSelection.classList.add("hidden");
            eggSelection.classList.remove("hidden");
        }

        eggMonsters.forEach(monster => {
            const monsterDiv = document.createElement("div");
            monsterDiv.classList.add("flex", "flex-col", "items-center", "w-32", "p-2", "bg-secondary", "border-2", "border-accent", "rounded-md", "cursor-pointer", "text-text");
            monsterDiv.innerHTML = `
                <div class="w-24 h-24 p-2 rounded-md bg-primary">
                    <img src="/storage/${monster.image_0}" class="w-full h-full object-cover" style="object-position: 0 0;" />
                </div>
                <p class="text-center">${monster.name}</p>
            `;

            monsterDiv.addEventListener("click", function() {
                selectedMonster = monster;
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
                monster_id: selectedMonster.id
            };
            fetch("{{ route('converge.create') }}", {
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