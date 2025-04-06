<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ $user->name }}
        </x-fonts.sub-header>
    </x-slot>

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Profile</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Manage your account settings below. You can update your name, email address, and password at any time. If you no longer wish to use your account, you also have the option to permanently delete it.
            </x-fonts.paragraph>
        </x-slot>

        <x-accordion title="Update Profile Information" :open="true" :icon="'fa-solid fa-user'">
            @include('profile.partials.update-profile-information-form')
        </x-accordion>

        <x-accordion title="Update Password" :open="false" :icon="'fa-solid fa-lock'">
            @include('profile.partials.update-password-form')
        </x-accordion>

        <x-accordion title="Delete Account" :open="false" :icon="'fa-solid fa-trash-alt'">
            @include('profile.partials.delete-user-form')
        </x-accordion>

        <div class="flex justify-center py-4">
            <a href="{{ route('profile.privacy') }}">
                <x-buttons.primary icon="fa-file-contract" label="Privacy" />
            </a>
        </div>
    </x-container>
</x-app-layout>