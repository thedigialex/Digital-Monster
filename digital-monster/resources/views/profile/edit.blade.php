<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ $user->name }}
            </x-fonts.sub-header>
        </div>
    </x-slot>

    <x-container>

        <div class="space-y-6">
            <x-accordion title="Update Profile Information" :open="false">
                @include('profile.partials.update-profile-information-form')
            </x-accordion>

            <x-accordion title="Update Password" :open="false">
                @include('profile.partials.update-password-form')
            </x-accordion>

            <x-accordion title="Delete Account" :open="false">
                @include('profile.partials.delete-user-form')
            </x-accordion>
        </div>
    </x-container>
</x-app-layout>