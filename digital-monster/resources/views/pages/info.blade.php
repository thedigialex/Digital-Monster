<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Information
        </x-fonts.sub-header>
        <a href="{{ route('digigarden') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>
    <x-container class="p-2 md:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Information</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                This site lets you collect, evolve, and manage digital creatures. Start by registering an account to receive dataCrystals, which you can use to choose an egg from the DigiConverge page. Hatch and evolve your creatures in the DigiGarden, track their progress, and build your ultimate digital team!
            </x-fonts.paragraph>
        </x-slot>
        <x-accordion title="Getting Started" :open="true" :icon="'fa-rocket'">
            <x-fonts.paragraph>Welcome! Here’s how to begin your journey in the world of digital monsters:</x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>1. Create your first monster</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Every new account starts with <strong>10 DataCrystals</strong>.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Head over to the <strong>DigiConverge</strong> page (found in the menu) to use your crystals and create a new monster egg.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>2. Hatch your egg</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Once your egg is created, visit the <strong>DigiGarden</strong> to view it alongside your other monsters.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Click on the egg to open its stats panel. You’ll see an <strong>Evolve</strong> button—click it to hatch your egg into a baby monster!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>3. Feed your monster</x-fonts.sub-header>
                <x-fonts.paragraph>
                    After hatching, your monster will be hungry! As a new player, you’ll start with <strong>25 meat items</strong> to feed your monster.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Feeding your monster fills its <strong>Evolution Bar</strong>, helping it grow and evolve to the next stage.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Need more food? Visit the <strong>Shop</strong> (also found in the menu) to buy additional meat and items.
                </x-fonts.paragraph>
            </div>
        </x-accordion>
        <x-accordion title="Training" :open="false" :icon="'fa-chart-line'">
            <x-fonts.paragraph>
                In the Digital World, monsters must train to survive! While your DigiGarden keeps them safe from danger, they’ll need to venture out to earn <strong>Bits</strong> for buying food and other items. To keep them safe and strong, you’ll need to boost their <strong>STATS</strong>!
            </x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>1. What are STATS?</x-fonts.sub-header>
                <x-fonts.paragraph>
                    There are four main stats your monsters rely on:
                </x-fonts.paragraph>
                <ul class="list-disc list-inside pl-4 text-text">
                    <li><strong>Strength</strong></li>
                    <li><strong>Defense</strong></li>
                    <li><strong>Agility</strong></li>
                    <li><strong>Mind</strong></li>
                </ul>
                <x-fonts.paragraph>
                    Every stat plays a key role in helping your monster win battles!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>2. How to increase STATS?</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Once your monster hatches, click on it in the <strong>DigiGarden</strong> to open the stats menu. You will see four training options—each linked to a different stat.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Select one to begin training!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>3. How does training work?</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Each training session opens a mini-game. Time your click when the bar is full to earn the biggest stat boost!
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Training equipment can also be upgraded to increase how much each session improves your monster.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>4. Virus, Data, Vaccine Types</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Different monster types excel in different stats.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Use these strengths to build your strategy!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>5. Stat Caps & Evolution</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Each evolution stage has a stat cap based on how many training sessions you have completed.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Once your monster reaches that stage's training limit, further training won't boost stats—until it evolves.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Bonus tip: Training your monster before evolution grants bonus stats after it evolves!
                </x-fonts.paragraph>
            </div>
        </x-accordion>

        <x-accordion title="Evolution" :open="false" :icon="'fa-dna'">
            <x-fonts.paragraph>
                Every monster has the potential to evolve into stronger, more unique forms! Evolution requires dedication—and Evolution Points!
            </x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>1. Evolution Stages</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Monsters evolve through the following stages:
                </x-fonts.paragraph>
                <x-fonts.paragraph class="italic text-text">
                    Egg → Fresh → Child → Rookie → Champion → Ultimate → Mega
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Note: Higher stages aren't always stronger—your monster's stats and strategy still matter!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>2. How to Evolve</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Monsters don't evolve automatically. To evolve to the next stage, they must earn <strong>Evolution Points</strong>.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Evolution Points can be gained by:
                </x-fonts.paragraph>
                <ul class="list-disc list-inside pl-4 text-text">
                    <li>Winning battles</li>
                    <li>Consuming special items</li>
                </ul>
                <x-fonts.paragraph>
                    Each stage requires more Evolution Points than the last—plan your growth!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>3. Branching Evolution Paths</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Starting from the <strong>Child</strong> stage, monsters can evolve down different routes depending on their current stats.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    For example, a monster with high Strength might evolve into a powerhouse, while one with high Mind might become a caster-type.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Every stage after Child offers two unique evolution options—your training decisions shape their destiny!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>4. Virus, Data, and Vaccine</x-fonts.sub-header>
                <x-fonts.paragraph>
                    From the Child stage onward, your monster will adopt a specific type: <strong>Virus</strong>, <strong>Data</strong>, or <strong>Vaccine</strong>.
                </x-fonts.paragraph>
            </div>
        </x-accordion>

        <x-accordion title="Adventure" :open="false" :icon="'fa-compass'">
            <x-fonts.paragraph>
                Once your monster reaches the <strong>Rookie stage</strong> or higher, they’re ready to leave the safety of the DigiGarden and explore the vast digital world!
            </x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>1. How Adventures Work</x-fonts.sub-header>
                <x-fonts.paragraph>
                    The digital world is full of mysteries, challenges, and rewards—but digitalization of matter isn't possible (yet), so only your monster can venture out.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Tap the <strong>Step</strong> button to send your monster forward on its journey. After a short time, they will report back with what they encountered.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    You can keep adventuring with the same monster or switch to another one once they return.
                </x-fonts.paragraph>
                <x-fonts.paragraph class="italic text-text">
                    Each step adds to your monster's total travel distance—helping unlock new milestones and rewards!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>2. Random Events</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Each step has a chance to trigger an event—ranging from finding a rare item to getting into a random battle!
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    The more you explore, the more types of events you will uncover. There may even be secret encounters...
                </x-fonts.paragraph>
            </div>
        </x-accordion>

        <x-accordion title="Upgrades" :open="false" :icon="'fa-microchip'">
            <x-fonts.paragraph>
                As you adventure through the digital world, you’ll discover special materials that allow you to upgrade your stat equipment and DigiGarden!
            </x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>1. Equipment Upgrades</x-fonts.sub-header>
                <x-fonts.paragraph>
                    All stat training equipment starts at <strong>Level 1</strong>.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Upgrading your equipment boosts the amount of stats your monster gains during training.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    To upgrade equipment, you will need specific materials. The cost increases with each level:
                </x-fonts.paragraph>
                <x-fonts.paragraph class="italic text-text">
                    You will need <strong>10 X current level</strong> of each required material to upgrade.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>2. Finding Materials</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Materials can only be found by sending your monsters on <strong>Adventures</strong>.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    The deeper the adventure, the rarer the materials you can discover!
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>3. DigiGarden Upgrades</x-fonts.sub-header>
                <x-fonts.paragraph>
                    Upgrading your DigiGarden expands its size, allowing you to raise more monsters at once.
                </x-fonts.paragraph>
                <x-fonts.paragraph>
                    Future upgrades may also unlock new features or decorative options—so keep collecting!
                </x-fonts.paragraph>
            </div>
        </x-accordion>

    </x-container>
</x-app-layout>