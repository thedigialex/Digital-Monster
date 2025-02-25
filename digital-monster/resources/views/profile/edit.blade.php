<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ $user->name }}
            </x-fonts.sub-header>
        </div>
    </x-slot>
    <x-container class="p-4">
        <x-accordion title="Update Profile Information" :open="true" :icon="'fa-solid fa-user'">
            @include('profile.partials.update-profile-information-form')
        </x-accordion>

        <x-accordion title="Update Password" :open="false" :icon="'fa-solid fa-lock'">
            @include('profile.partials.update-password-form')
        </x-accordion>

        <x-accordion title="Delete Account" :open="false" :icon="'fa-solid fa-trash-alt'">
            @include('profile.partials.delete-user-form')
        </x-accordion>
    </x-container>

</x-app-layout>