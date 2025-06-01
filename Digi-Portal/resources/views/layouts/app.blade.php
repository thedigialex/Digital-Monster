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
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-XXXXXXXXXX');
    </script>
</head>

<body class="font-sans antialiased selection:bg-accent square-grid-bg">
    @if (Auth::check())
    <div class="flex flex-col md:flex-row min-h-screen">
        <aside class="hidden md:flex fixed top-0 left-0 w-64 bg-gradient-to-t from-primary to-tertiary h-screen flex-col">
            <div class="border-b-4 border-secondary p-6 flex items-center justify-center space-x-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="rounded-md w-10 h-10" />
                <x-fonts.sub-header class="flex items-center">
                    {{ config('app.name') }}
                </x-fonts.sub-header>
            </div>

            @include('layouts.navigation')
        </aside>

        @include('layouts.mobile_navigation')

        <div class="flex-1 md:ml-64 overflow-hidden">
            <div class="md:fixed md:top-0 w-full md:w-[calc(100%-16rem)] bg-gradient-to-l from-primary to-tertiary border-b-4 border-accent p-6 z-10 flex justify-between items-center">
                {{ $header }}
            </div>

            <main class="md:mt-[92px] overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    @else
    {{ $slot }}
    @endif
</body>

</html>