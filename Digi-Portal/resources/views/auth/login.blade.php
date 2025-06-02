<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('login') }}" id="login-form" class="flex flex-col gap-4 px-4">
    @csrf
    <div class="pb-4">
        <x-fonts.sub-header>Welcome back</x-fonts.sub-header>
        <x-fonts.paragraph>Login to the Digi-Portal to reconnect with your digital monsters.</x-fonts.paragraph>
    </div>
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

    <div class="flex items-center justify-center pt-4">
        <x-buttons.button type="edit" icon="fa-sign-in" label="Login" />
    </div>
    <x-copyright />
</form>