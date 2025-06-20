<nav x-data="{ open: false }" class="md:hidden bg-primary">
    <div class="px-6 py-4 border-b-4 border-secondary flex justify-between">
        <a href="{{ route('digigarden') }}" class="flex items-center justify-center space-x-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="rounded-md w-10 h-10" />
            <x-fonts.sub-header class="flex items-center">
                {{ config('app.name') }}
            </x-fonts.sub-header>
        </a>
        <div class="flex items-center sm:hidden">
            <x-buttons.button type="edit" x-show="!open" @click="open = true" icon="fa-bars" label="Menu" />
            <x-buttons.button type="edit" x-show="open" @click="open = false" icon="fa-x" label="Menu" />
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-secondary">
        <div class="bg-gradient-to-l from-primary to-tertiary border-b-4 border-accent p-6 z-10 flex justify-between items-center">
            {{ $header }}
        </div>
        @include('layouts.navigation')
    </div>
</nav>