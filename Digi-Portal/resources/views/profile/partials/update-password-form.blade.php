<section>
    <header>
        <x-fonts.accent-header>Update Password</x-fonts.accent-header>
        <x-fonts.paragraph>Ensure your account is using a long, random password to stay secure.</x-fonts.paragraph>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <x-inputs.text name="current_password" type="password" divClasses="w-full" :messages="$errors->updatePassword->get('current_password')" autocomplete="current-password" />

        <x-inputs.text name="password" type="password" divClasses="w-full" :messages="$errors->updatePassword->get('password')" />

        <x-inputs.text name="password_confirmation" type="password" divClasses="w-full" :messages="$errors->updatePassword->get('password_confirmation')" />

        <div class="flex items-center justify-center my-4">
            <x-buttons.button type="edit" label="Update" icon="fa-save" />
            @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>