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
        <x-container.background :background="$background" :timeOfDay="$timeOfDay">
            <x-alerts.spinner id="loading-section"></x-alerts.spinner>

            <div id="monster-section" class="flex flex-col items-center gap-4 w-full">
                <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">{{ $message }}</x-fonts.paragraph>
                @if(!$userMonsters->isEmpty())
                <div class="flex justify-center items-center gap-2 w-full">
                    <x-buttons.button type="edit" id="scrollLeft" label="" icon="fa-chevron-left" />
                    <div id="monsterScroll" class="flex  transition-transform duration-300 w-3/4 lg:w-1/3 gap-4 overflow-hidden bg-primary p-4 rounded-md">
                        @foreach ($userMonsters as $userMonster)
                        <x-container.monster-card :data-monster="$userMonster" :id="'monster-' . $userMonster->id" buttonClass="selectUserMonster" divClass="monster-div" />
                        @endforeach
                    </div>
                    <x-buttons.button type="edit" id="scrollRight" label="" icon="fa-chevron-right" />
                </div>
                @endif
            </div>
            <div id="confirm-selection" class="hidden flex flex-col justify-center items-center gap-4">
                <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">Extract DataCore from this Monster?</x-fonts.paragraph>
                <x-buttons.button type="edit" id="confirmButton" label="Confirm" icon="fa-check" />
            </div>
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const confirmSelection = document.getElementById("confirm-selection");
        const monsterSection = document.getElementById("monster-section");
        const scrollLeftBtn = document.getElementById("scrollLeft");
        const scrollRightBtn = document.getElementById("scrollRight");

        let selectedMonster;

        scrollLeftBtn?.addEventListener('click', () => scrollCards(-1));
        scrollRightBtn?.addEventListener('click', () => scrollCards(1));

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

        document.querySelectorAll('.selectUserMonster').forEach(button => {
            button.addEventListener('click', function() {
                selectedMonster = JSON.parse(this.getAttribute("data-monster"));
                confirmSelection.classList.remove("hidden");
                highlightUserMonster();
            });
        });

        function highlightUserMonster() {
            document.querySelectorAll(".monster-div").forEach(container => {
                const monsterText = container.querySelector("p");
                container.classList.remove("bg-accent", "bg-secondary");
                monsterText.classList.remove("text-secondary", "text-text");
                const userMonster = JSON.parse(container.querySelector("button").getAttribute("data-monster"));
                if (selectedMonster.id === userMonster.id) {
                    container.classList.add("bg-accent");
                    monsterText.classList.add("text-text");
                } else {
                    container.classList.add("bg-secondary");
                    monsterText.classList.add("text-secondary");
                }
            });
        }

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
    });
</script>