<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}">
    @csrf
    <x-fonts.sub-header class="mb-4">Login</x-fonts.sub-header>

    <!-- Email Address -->
    <div>
        <x-inputs.label for="email" :value="__('Email')" />
        <x-inputs.text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-inputs.label for="password" :value="__('Password')" />

        <x-inputs.text id="password" class="block mt-1 w-full"
            type="password"
            name="password"
            required autocomplete="current-password" />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-buttons.primary>
            {{ __('Log in') }}
        </x-buttons.primary>
    </div>
</form>