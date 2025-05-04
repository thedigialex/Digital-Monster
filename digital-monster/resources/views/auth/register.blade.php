<form method="POST" action="{{ route('register') }}">
    @csrf
    <x-fonts.sub-header class="mb-4">Register</x-fonts.sub-header>
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
        :value="old('email')"
        :messages="$errors->get('email')" />

    <x-inputs.text
        class="w-full"
        type="password"
        name="password"
        :messages="$errors->get('password')" />

    <x-inputs.text
        class="w-full"
        type="password"
        name="password_confirmation"
        :messages="$errors->get('password_confirmation')" />

    <div class="flex items-center justify-center py-4">
        <x-buttons.button type="edit" icon="fa-user-plus" label="Register" />
    </div>
</form>