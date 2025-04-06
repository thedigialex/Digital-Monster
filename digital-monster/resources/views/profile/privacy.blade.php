<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Privacy
        </x-fonts.sub-header>
        <a href="{{ route('profile.edit') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>
    @if (session('error'))
        <x-alerts.error :message="session('error')" />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Privacy</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Privacy stuff
            </x-fonts.paragraph>
        </x-slot>
        <x-accordion title="Privacy" :open="true" :icon="'fa fa-user'">
            <x-fonts.paragraph class="text-text p-4">placeholder</x-fonts.paragraph>
            <form method="POST" action="{{ route('profile.privacy.update') }}">
                @csrf
                <div class="flex flex-col items-center gap-4 py-4 text-center">
                    <label for="privacy_accept" class="flex items-center justify-center">
                        <input
                            id="privacy_accept"
                            name="privacy_accept"
                            type="checkbox"
                            class="rounded-md text-accent shadow-sm focus:ring-accent"
                            {{ $user->privacy_accept ? 'checked' : '' }} />
                        <span class="ml-2 text-sm text-text">I agree to the privacy policy</span>
                    </label>
                    <x-buttons.primary type="submit" label="Update" icon="fa-file-signature" />
                </div>
            </form>
        </x-accordion>
    </x-container>
</x-app-layout>