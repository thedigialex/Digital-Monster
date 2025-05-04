<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <x-fonts.sub-header class="mb-4">Forgot your password?</x-fonts.sub-header>

    <x-inputs.text
        class="w-full"
        type="email"
        name="email"
        :value="old('email')"
        :messages="$errors->get('email')" />

    <div class="flex items-center justify-center py-4">
        <x-buttons.button type="edit" icon="fa-envelope" label="Reset" />
    </div>
</form>