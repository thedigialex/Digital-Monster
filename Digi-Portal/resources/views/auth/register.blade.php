<form method="POST" action="{{ route('register') }}" id="register-form" class="flex flex-col gap-4 md:px-4">
    @csrf
    <div class="pb-4">
        <x-fonts.sub-header>You've Discovered the Digi-Portal</x-fonts.sub-header>
        <x-fonts.paragraph>
            This gateway grants access to a digital realm. A world where powerful digital monsters await your guidance.
            Help them grow stronger and defend this reality. Create your access credentials below to begin your journey.
        </x-fonts.paragraph>
    </div>
    <x-inputs.text
        class="w-full"
        type="text"
        name="name"
        :value="old('name')"
        :messages="$errors->get('name')" />
    <x-inputs.text
        class="w-full"
        type="email"
        name="email"
        id="email-register"
        :value="old('email')"
        :messages="$errors->get('email')" />
    <x-inputs.text
        class="w-full"
        type="password"
        name="password"
        id="password-register"
        :messages="$errors->get('password')" />
    <x-inputs.text
        class="w-full"
        type="password"
        name="password_confirmation"
        :messages="$errors->get('password_confirmation')" />
    <div class="flex items-center justify-center pt-4">
        <x-buttons.button type="edit" icon="fa-user-plus" label="Register" />
    </div>
    <x-copyright />
</form>