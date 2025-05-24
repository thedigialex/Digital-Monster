<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}">
    @csrf
    <x-fonts.sub-header class="mb-4">Login</x-fonts.sub-header>

    <x-inputs.text
        class="w-full"
        type="email"
        name="email"
        id="email-login"
        :value="old('email')"
        :messages="$errors->get('email')" />

    <x-inputs.text
        class="w-full"
        type="password"
        name="password"
        id="password-login"
        :messages="$errors->get('password')" />

    <div class="flex items-center justify-center py-4">
        <x-buttons.button type="edit" icon="fa-sign-in" label="Log in" />
    </div>
</form>