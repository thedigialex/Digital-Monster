<nav x-data="{ open: false }" class="bg-primary">
    <div class="px-6 py-4 border-b-4 border-secondary flex justify-between">
        <a href="{{ route('digigarden') }}">
            <x-application-logo class="rounded-md h-9 w-auto fill-current" />
        </a>
        <x-fonts.sub-header>Digital Portal</x-fonts.sub-header>

        <div class="flex items-center sm:hidden">
            <x-buttons.primary x-show="!open" @click="open = true" icon="fa-bars" label="Menu" />
            <x-buttons.primary x-show="open" @click="open = false" icon="fa-x" label="Menu" />
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-secondary rounded-b-md">
        @include('layouts.navigation')
        <form method="POST" action="{{ route('logout') }}" class="mt-auto flex flex-col items-center justify-center border-t-4 border-accent py-4 gap-y-2">
            @csrf
            <x-buttons.primary type="submit" icon="fa-sign-out" label="Log Out" />
        </form>
    </div>
</nav>