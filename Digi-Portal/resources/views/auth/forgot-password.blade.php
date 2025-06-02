<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('password.email') }}" id="forgot-form" class="flex flex-col gap-4 px-4">
    @csrf
    <div class="pb-4">
        <x-fonts.sub-header>Lost Access?</x-fonts.sub-header>
        <x-fonts.paragraph>
            No worries, Tamer. Enter your email below and the Digi-Portal will dispatch a recovery link to help you regain access.
        </x-fonts.paragraph>
    </div>
    <x-inputs.text
        class="w-full"
        type="email"
        name="email"
        id="email-password"
        :value="old('email')"
        :messages="$errors->get('email')" />
    <div class="flex items-center justify-center pt-4">
        <x-buttons.button type="edit" icon="fa-envelope" label="Reset" />
    </div>
    <x-copyright />
</form>