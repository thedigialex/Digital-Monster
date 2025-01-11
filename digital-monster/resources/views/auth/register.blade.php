<form method="POST" action="{{ route('register') }}">
    @csrf
    <x-fonts.sub-header class="mb-4">Register</x-fonts.sub-header>
    <!-- Name -->
    <div>
        <x-inputs.label for="name" :value="__('Name')" />
        <x-inputs.text id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div class="mt-4">
        <x-inputs.label for="email" :value="__('Email')" />
        <x-inputs.text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div class="mt-4">
        <x-inputs.label for="password" :value="__('Password')" />

        <x-inputs.text id="password" class="block mt-1 w-full"
            type="password"
            name="password"
            required autocomplete="new-password" />

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <x-inputs.label for="password_confirmation" :value="__('Confirm Password')" />

        <x-inputs.text id="password_confirmation" class="block mt-1 w-full"
            type="password"
            name="password_confirmation" required autocomplete="new-password" />

        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ __('Register') }}
        </x-primary-button>
    </div>
</form>