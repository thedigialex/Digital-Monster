<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Digital Gate</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased selection:bg-cyan-500 h-screen bg-secondary">
    <div class="h-full flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-6 lg:p-32">
            <div class="bg-neutral p-4 lg:p-8 rounded">
                <div x-data="{ activeTab: 'login' }">
                    <!-- Tab Navigation -->
                    <div class="flex justify-center bg-neutral pt-4">
                        <button
                            :class="{'neutral-b-2 neutral-accent text-accent': activeTab === 'login', 'text-text': activeTab !== 'login'}"
                            class="py-2 px-4 focus:outline-none"
                            @click="activeTab = 'login'">
                            Login
                        </button>
                        <button
                            :class="{'neutral-b-2 neutral-accent text-accent': activeTab === 'register', 'text-text': activeTab !== 'register'}"
                            class="py-2 px-4 focus:outline-none"
                            @click="activeTab = 'register'">
                            Register
                        </button>
                        <button
                            :class="{'neutral-b-2 neutral-accent text-accent': activeTab === 'forgot-password', 'text-text': activeTab !== 'forgot-password'}"
                            class="py-2 px-4 focus:outline-none"
                            @click="activeTab = 'forgot-password'">
                            Forgot Password?
                        </button>
                    </div>

                    <div class="mt-8">
                        <!-- Login Form -->
                        <div x-show="activeTab === 'login'">
                            <x-elements.container :title="'Sign In'">
                                <x-fonts.paragraph>Welcome! Please enter your details.</x-fonts.paragraph>
                                @include('auth.login')
                            </x-elements.container>
                        </div>

                        <!-- Register Form -->
                        <div x-show="activeTab === 'register'">
                            <x-elements.container :title="'Sign Up'">
                                <x-fonts.paragraph>Welcome! Please enter your details.</x-fonts.paragraph>
                                @include('auth.register')
                            </x-elements.container>
                        </div>

                        <!-- Forgot Password Form -->
                        <div x-show="activeTab === 'forgot-password'">
                            <x-elements.container :title="'Forgot your password?'">
                                <x-fonts.paragraph>Please enter your details.</x-fonts.paragraph>
                                @include('auth.forgot-password')
                            </x-elements.container>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:flex flex-col lg:w-1/2 bg-primary items-center justify-center">
            <x-elements.application-logo class="fill-current" width="400" height="400" />
        </div>
    </div>
</body>

</html>