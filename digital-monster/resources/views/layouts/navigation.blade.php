<nav class="flex-1 mt-4 space-y-4">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </x-nav-link>

    <x-nav-link :href="route('colosseum')" :active="request()->routeIs('colosseum')">
        <i class="fas fa-landmark"></i>
        <span>Colosseum</span>
    </x-nav-link>

    <x-nav-link :href="route('shop')" :active="request()->routeIs('shop')">
        <i class="fa-solid fa-store"></i>
        <span>Shop</span>
    </x-nav-link>

    @if (Auth::user()->role === 'admin')
    <x-nav-link :href="route('egg_groups.index')" :active="request()->routeIs(['egg_groups.index', 'egg_group.edit'])">
        <i class="fas fa-egg"></i>
        <span>Egg Groups</span>
    </x-nav-link>

    <x-nav-link :href="route('monsters.index')" :active="request()->routeIs(['monsters.index', 'monster.edit'])">
        <i class="fas fa-dragon"></i>
        <span>Monsters</span>
    </x-nav-link>

    <x-nav-link :href="route('items.index')" :active="request()->routeIs(['items.index', 'item.edit'])">
        <i class="fas fa-box-open"></i>
        <span>Items</span>
    </x-nav-link>

    <x-nav-link :href="route('equipment.index')" :active="request()->routeIs(['equipment.index', 'equipment.edit'])">
        <i class="fas fa-dumbbell"></i>
        <span>Equipment</span>
    </x-nav-link>

    <x-nav-link :href="route('users.index')" :active="request()->routeIs(['users.index', 'user.item.edit', 'user.equipment.edit', 'user.monster.edit', 'user.profile'])">
        <i class="fas fa-users"></i>
        <span>Users</span>
    </x-nav-link>
    @endif

    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs(['profile.edit'])">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </x-nav-link>
</nav>