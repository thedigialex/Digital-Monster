<nav class="flex-1 mt-4 space-y-4">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        <i class="fas fa-tachometer-alt"></i>
        <span>{{ __('Dashboard') }}</span>
    </x-nav-link>

    @if (Auth::user()->role === 'admin')
    <x-nav-link :href="route('egg_groups.index')" :active="request()->routeIs(['egg_groups.index', 'egg_groups.edit'])">
        <i class="fas fa-egg"></i>
        <span>{{ __('Egg Groups') }}</span>
    </x-nav-link>

    <x-nav-link :href="route('digital_monsters.index')" :active="request()->routeIs('digital_monsters.index')">
        <i class="fas fa-dragon"></i>
        <span>{{ __('Digital Monsters') }}</span>
    </x-nav-link>

    <x-nav-link :href="route('items.index')" :active="request()->routeIs(['items.index', 'items.edit'])">
        <i class="fas fa-box-open"></i>
        <span>{{ __('Items') }}</span>
    </x-nav-link>

    <x-nav-link :href="route('trainingEquipments.index')" :active="request()->routeIs('trainingEquipments.index')">
        <i class="fas fa-dumbbell"></i>
        <span>{{ __('Training Equipment') }}</span>
    </x-nav-link>

    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
        <i class="fas fa-users"></i>
        <span>{{ __('Users') }}</span>
    </x-nav-link>
    @endif

    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
        <i class="fas fa-user"></i>
        <span>{{ __('Account') }}</span>
    </x-nav-link>
</nav>