<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <x-fonts.sub-header class="mb-4">Forgot your password?</x-fonts.sub-header>
    <!-- Email Address -->
    <div>
        <x-inputs.label for="email" :value="__('Email')" />
        <x-inputs.text id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-buttons.primary>
            {{ __('Email Password Reset Link') }}
        </x-buttons.primary>
    </div>
</form>