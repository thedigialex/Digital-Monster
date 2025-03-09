<section>
    <header>
        <x-fonts.accent-header>Profile Information</x-fonts.accent-header>
        <x-fonts.paragraph>Update your account's profile information and email address.</x-fonts.paragraph>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')
        <x-inputs.text name="name" divClasses="w-full" :value="old('name', $user->name)" :messages="$errors->get('name')" />
        <div>
            <x-inputs.text name="email" divClasses="w-full" :value="old('email', $user->email)" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="flex flex-col space-y-2">
                <div class="flex items-center space-x-4">
                    <x-fonts.paragraph class="flex-grow">Your email address is unverified.</x-fonts.paragraph>
                    <button form="send-verification" class="w-1/4 underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </div>
                @if (session('status') === 'verification-link-sent')
                <x-fonts.paragraph>
                    {{ __('A new verification link has been sent to your email address.') }}
                </x-fonts.paragraph>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center justify-center my-4">
            <x-buttons.primary label="Update" icon="fa-save" />
            @if (session('status') === 'profile-updated')
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