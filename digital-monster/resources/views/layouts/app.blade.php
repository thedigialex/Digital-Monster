<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Digital Portal') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased square-grid-bg">
    <div class="min-h-screen">
        @if (Auth::user()->role === 'admin')
        @include('layouts.admin_navigation')
        @else
        @include('layouts.navigation')
        @endif

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-secondary shadow border-b-4 border-accent">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

<body class="font-sans antialiased">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-primary h-screen shadow-lg flex flex-col">
            <div class="border-b-4 border-secondary p-6 text-center">
                <x-fonts.sub-header>Digital Portal</x-fonts.sub-header>
            </div>

            <nav class="flex-1 mt-4 space-y-4">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" >
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span>
                </x-nav-link>

                <x-nav-link :href="route('egg_groups.index')" :active="request()->routeIs('egg_groups.index')" >
                    <i class="fas fa-egg"></i>
                    <span>{{ __('Egg Groups') }}</span>
                </x-nav-link>

                <x-nav-link :href="route('digital_monsters.index')" :active="request()->routeIs('digital_monsters.index')" >
                    <i class="fas fa-dragon"></i>
                    <span>{{ __('Digital Monsters') }}</span>
                </x-nav-link>

                <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')" >
                    <i class="fas fa-box-open"></i>
                    <span>{{ __('Items') }}</span>
                </x-nav-link>

                <x-nav-link :href="route('trainingEquipments.index')" :active="request()->routeIs('trainingEquipments.index')" >
                    <i class="fas fa-dumbbell"></i>
                    <span>{{ __('Training Equipment') }}</span>
                </x-nav-link>

                <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" >
                    <i class="fas fa-users"></i>
                    <span>{{ __('Users') }}</span>
                </x-nav-link>
            </nav>


            <div class="border-t-2 border-accent p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-text hover:text-accent">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col square-grid-bg">
            @isset($header)
            <header class="bg-secondary shadow border-b-4 border-accent p-6">
                <div class="max-w-7xl mx-auto">
                    {{ $header }}
                </div>
            </header>
            @endisset
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>


</html>