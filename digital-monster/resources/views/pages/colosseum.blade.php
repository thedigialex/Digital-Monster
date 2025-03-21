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
        <div class="p-4 gap-4 flex justify-center items-center">
            <button id="scrollLeft" class="p-4 bg-secondary text-text hover:text-accent rounded-md">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="flex items-center overflow-hidden gap-4 transition-transform duration-300 ease-in-out" data-monsters='@json($userMonsters)' id="monsterCarousel">
            </div>
            <button id="scrollRight" class="p-4 bg-secondary text-text hover:text-accent rounded-md">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </x-container>

</x-app-layout>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const carousel = document.getElementById("monsterCarousel");
        const scrollLeftBtn = document.getElementById("scrollLeft");
        const scrollRightBtn = document.getElementById("scrollRight");

        const userMonsters = JSON.parse(carousel.getAttribute('data-monsters'));
        let itemsPerPage = window.innerWidth <= 768 ? 1 : 5;
        let currentIndex = 0;
        let activeUserMonster;

        function renderMonsters() {
            carousel.innerHTML = '';

            const monstersToShow = userMonsters.slice(currentIndex, currentIndex + itemsPerPage);

            monstersToShow.forEach(userMonster => {
                const monsterDiv = document.createElement('div');
                monsterDiv.classList.add('flex', 'flex-col', 'items-center', 'w-28', 'p-2', 'bg-secondary', 'border-2', 'border-accent', 'rounded-md', 'cursor-pointer');

                monsterDiv.addEventListener('click', function() {
                    if (activeUserMonster) {
                        const previousActiveMonsterDiv = document.querySelector(`#monster-${activeUserMonster.id}`);
                        if (previousActiveMonsterDiv) {
                            previousActiveMonsterDiv.classList.remove('bg-accent');
                            previousActiveMonsterDiv.classList.add('bg-secondary');
                        }
                    }

                    activeUserMonster = userMonster;

                    monsterDiv.classList.remove('bg-secondary');
                    monsterDiv.classList.add('bg-accent');
                });

                monsterDiv.id = `monster-${userMonster.id}`;

                const imgDiv = document.createElement('div');
                imgDiv.classList.add('w-24', 'h-24', 'p-2', 'rounded-md', 'bg-primary');

                const img = document.createElement('img');
                img.classList.add('w-full', 'h-full', 'object-cover');
                if (['Egg', 'Fresh', 'Child'].includes(userMonster.monster.stage) || userMonster.type === 'Data') {
                    img.src = `/storage/${userMonster.monster.image_0}`;
                } else if (userMonster.type === 'Virus') {
                    img.src = `/storage/${userMonster.monster.image_1}`;
                } else if (userMonster.type === 'Vaccine') {
                    img.src = `/storage/${userMonster.monster.image_2}`;
                }
                img.style.objectPosition = "0 0";
                imgDiv.appendChild(img);

                const nameParagraph = document.createElement('p');
                nameParagraph.classList.add('text-center', 'text-text');
                nameParagraph.textContent = userMonster.name;

                monsterDiv.appendChild(imgDiv);
                monsterDiv.appendChild(nameParagraph);

                carousel.appendChild(monsterDiv);
            });
        }

        scrollLeftBtn.addEventListener("click", function() {
            currentIndex -= itemsPerPage;
            if (currentIndex < 0) currentIndex = 0;
            renderMonsters();
        });

        scrollRightBtn.addEventListener("click", function() {
            currentIndex += itemsPerPage;
            if (currentIndex >= userMonsters.length) currentIndex = userMonsters.length - itemsPerPage;
            renderMonsters();
        });

        window.addEventListener('resize', function() {
            itemsPerPage = window.innerWidth <= 768 ? 1 : 5;
            currentIndex = 0;
            renderMonsters();
        });

        renderMonsters();
    });
</script>