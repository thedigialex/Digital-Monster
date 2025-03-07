<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased selection:bg-accent">
        <!-- Mobile Layout -->
        <div class="md:hidden">
            @include('layouts.mobile_navigation')
            <div class="flex flex-col min-h-screen square-grid-bg">
                <header class="bg-primary border-b-4 border-accent p-6">
                    <div class="flex justify-between items-center">
                        {{ $header }}
                    </div>
                </header>

                <main class="overflow-y-auto p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Desktop Layout -->
        <div class="hidden md:flex flex-row min-h-screen">
            <aside class="fixed top-0 left-0 w-64 bg-primary min-h-screen flex flex-col overflow-y-auto">
                <div class="border-b-4 border-secondary p-6 text-center">
                    <x-fonts.sub-header>Digital Portal</x-fonts.sub-header>
                </div>

                @include('layouts.navigation')

                <div class="mt-auto flex flex-col items-center justify-center border-t-4 border-secondary p-4 gap-y-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-buttons.primary type="submit" icon="fa-sign-out" label="Log Out" />
                    </form>
                    <x-copyright />
                </div>
            </aside>

            <div class="flex-1 ml-64 overflow-hidden square-grid-bg">
                @isset($header)
                <header class="fixed top-0 left-64 w-[calc(100%-16rem)] bg-primary border-b-4 border-accent p-6 z-10">
                    <div class="flex justify-between items-center">
                        {{ $header }}
                    </div>
                </header>
                @endisset

                <main class="mt-24 h-[calc(100vh-6rem)] overflow-y-auto p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>