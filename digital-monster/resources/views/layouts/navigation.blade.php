<nav class="flex-1 mt-4 space-y-4 overflow-y-auto px-4 pb-4">
    <x-nav-link :href="route('digigarden')" :active="request()->routeIs('digigarden')">
        <i class="fa fa-hard-drive"></i>
        <span>DigiGarden</span>
    </x-nav-link>

    <x-nav-link :href="route('digiconverge')" :active="request()->routeIs('digiconverge')">
        <i class="fa-solid fa-compress-arrows-alt"></i>
        <span>DigiConverge</span>
    </x-nav-link>

    <x-nav-link :href="route('colosseum')" :active="request()->routeIs('colosseum')">
        <i class="fas fa-landmark"></i>
        <span>Colosseum</span>
    </x-nav-link>

    <x-nav-link :href="route('adventure')" :active="request()->routeIs('adventure')">
        <i class="fas fa-compass"></i>
        <span>Adventure</span>
    </x-nav-link>

    <x-nav-link :href="route('shop')" :active="request()->routeIs('shop')">
        <i class="fa-solid fa-store"></i>
        <span>Shop</span>
    </x-nav-link>

    <x-nav-link :href="route('upgradeStation')" :active="request()->routeIs('upgradeStation')">
        <i class="fa-solid fa-microchip"></i>
        <span>Upgrade Station</span>
    </x-nav-link>

    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs(['profile.edit', 'profile.policy'])">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </x-nav-link>

    <x-nav-link :href="route('users.index')" :active="request()->routeIs(['users.index', 'user.item.edit', 'user.equipment.edit', 'user.monster.edit', 'user.profile', 'digigarden.user'])">
        <i class="fas fa-globe"></i>
        <span>Users</span>
    </x-nav-link>

    @if (Auth::user()->role === 'admin')

    <x-fonts.accent-header>ADMIN</x-fonts.accent-header>

    <x-nav-link :href="route('egg_groups.index')" :active="request()->routeIs(['egg_groups.index', 'egg_group.edit'])">
        <i class="fas fa-egg"></i>
        <span>Egg Groups</span>
    </x-nav-link>

    <x-nav-link :href="route('monsters.index')" :active="request()->routeIs(['monsters.index', 'monster.edit'])">
        <i class="fas fa-dragon"></i>
        <span>Monsters</span>
    </x-nav-link>

    <x-nav-link :href="route('events.index')" :active="request()->routeIs(['events.index'])">
        <i class="fas fa-calendar"></i>
        <span>Events</span>
    </x-nav-link>

    <x-nav-link :href="route('items.index')" :active="request()->routeIs(['items.index', 'item.edit'])">
        <i class="fas fa-tags"></i>
        <span>Items</span>
    </x-nav-link>

    <x-nav-link :href="route('equipment.index')" :active="request()->routeIs(['equipment.index', 'equipment.edit'])">
        <i class="fas fa-toolbox"></i>
        <span>Equipment</span>
    </x-nav-link>
    @endif
</nav>