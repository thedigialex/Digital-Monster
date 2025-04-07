<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Information
        </x-fonts.sub-header>
    </x-slot>
    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Information</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                This site lets you collect, evolve, and manage digital creatures. Start by registering an account to receive dataCrystals, which you can use to choose an egg from the DigiConverge page. Hatch and evolve your creatures in the DigiGarden, track their progress, and build your ultimate digital team!
            </x-fonts.paragraph>
        </x-slot>

        <x-accordion title="Getting Started" :open="true" :icon="'fa-rocket'">
            <x-fonts.paragraph>Welcome! Here's how to begin your journey:</x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>1. Create your first monster</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Each account starts with <strong>10 dataCrystals</strong>.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Use your dataCrystals to select your first egg on the DigiConverge page.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>2. Hatch your egg</x-fonts.sub-header>
                <x-fonts.paragraph>
                    After selecting an egg, you'll be taken to the DigiGarden. There you will be able to see all of your current monsters.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Clicking on your new egg will open stats menu. There you can see your monsters stats and hatch your egg!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>3. Feed your mosnter</x-fonts.sub-header>
                <x-fonts.paragraph>
                    After hatching your mosnter will be hungery! New players are given 25 meats to feed thier freshly hatched mosnters.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Feeding your monster will increase your monster evolution bar!
                </x-fonts.paragraph>
            </div>
        </x-accordion>

        <x-accordion title="Training" :open="false" :icon="'fa-chart-line'">
            <x-fonts.paragraph>To rise up the ranks your monsters need those STATS!</x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>1. What are STATS?</x-fonts.sub-header>
                <x-fonts.paragraph>There are four STATS: Strenght, Agility, Defense, and Mind. 
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Each one is important to win battles!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>2. How to increase STATS?</x-fonts.sub-header>
                <x-fonts.paragraph>Clicking on a monster in the DigiGarden will open a stat menu, there will be the four buttons to start training.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>3. How does training work?</x-fonts.sub-header>
                <x-fonts.paragraph>
                    After clicking start a bar will move, stopping the bar when it's full will give the best stat increase.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Each training equipment can be leveled up increase the overall stat gain.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>4. VIRUS, DATA, VACCINE</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Each monster type will gain a slight bonus in certain stats.
                </x-fonts.paragraph>
            </div>
        </x-accordion>
    </x-container>
</x-app-layout>