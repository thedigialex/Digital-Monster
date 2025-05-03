<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Website Policy
        </x-fonts.sub-header>
        <a href="{{ route('profile.edit') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>
    
    @if (session('error'))
    <x-alerts.alert type="Info" :message="session('error')"  />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Website Policy</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                To use this site, you must agree to our Website Policy.
            </x-fonts.paragraph>
            @if ($user->policy_accept == 1)
            <a href="{{ route('info') }}" class="flex flex-col items-center gap-4 py-4 text-center">
                <x-buttons.primary icon="fa-circle-info" label="Info" />
            </a>
            @endif
        </x-slot>
        <x-accordion title="Website Policy" :open="true" :icon="'fa fa-file-contract'">
            <x-fonts.paragraph><strong>Effective Date:</strong> 4/6/2025</x-fonts.paragraph>

            <div class="py-4">
                <x-fonts.sub-header>Information We Collect</x-fonts.sub-header>
                <x-fonts.paragraph>When you create an account on our site, we collect only the basic information needed to set up and manage your user profile:</x-fonts.paragraph>
                <ul class="p-4">
                    <li><x-fonts.paragraph>Username</x-fonts.paragraph></li>
                    <li><x-fonts.paragraph>Email address</x-fonts.paragraph></li>
                    <li><x-fonts.paragraph>Password</x-fonts.paragraph></li>
                </ul>
                <x-fonts.paragraph>We strongly recommend using a unique password that is not shared across other accounts or services.</x-fonts.paragraph>
                <x-fonts.paragraph>Your personal data is used solely to manage your account and provide access to site features. We will never sell or share your account data with third parties.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Analytics</x-fonts.sub-header>
                <x-fonts.paragraph>We use Google Analytics to track general usage patterns on our website. This includes data such as pages visited, time spent on the site, and browser/device type. This data is collected anonymously and used to improve user experience and site performance.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Data Deletion</x-fonts.sub-header>
                <x-fonts.paragraph>You may delete your account and all associated data at any time by visiting your Profile Page. Once deleted, your data is permanently removed from our system and cannot be recovered.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Email Notifications</x-fonts.sub-header>
                <x-fonts.paragraph>We may send automated email notifications based on site activity in the future. These notifications will only be sent to users who have explicitly opted in through their Profile Page settings.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>User Behavior and Account Suspension</x-fonts.sub-header>
                <x-fonts.paragraph>To maintain a respectful and safe environment, we reserve the right to suspend or ban user accounts based on behavior that violates our community standards. This includes, but is not limited to, harassment, spamming, or abusive conduct. Users may receive up to three warnings (account locks) before permanent termination. We reserve the right to interpret and enforce these rules as needed.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Policy Updates</x-fonts.sub-header>
                <x-fonts.paragraph>This policy may be updated occasionally to reflect changes to the site or legal requirements. Any updates will be posted here with a new effective date. When changes are made, users will be required to re-accept the updated policy in order to continue using the site.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Contact Us</x-fonts.sub-header>
                <x-fonts.paragraph>If you have any questions or concerns about this Website Policy, feel free to contact us at <a href="mailto:thedigialex@gmail.com">thedigialex@gmail.com</a>.</x-fonts.paragraph>
            </div>

            @if ($user->policy_accept != 1)
            <form method="POST" action="{{ route('profile.policy.update') }}">
                @csrf
                <div class="flex flex-col items-center gap-4 py-4 text-center">
                    <label for="policy_accept" class="flex items-center justify-center">
                        <input
                            id="policy_accept"
                            name="policy_accept"
                            type="checkbox"
                            class="rounded-md text-accent shadow-sm focus:ring-accent"
                            {{ $user->policy_accept ? 'checked' : '' }} />
                        <span class="ml-2 text-sm text-text">I agree to the policy</span>
                    </label>
                    <x-buttons.primary type="submit" label="Accept" icon="fa-file-signature" />
                </div>
            </form>
            @else
            <div class="flex flex-col items-center gap-4 py-4 text-center">
                <x-fonts.paragraph class="text-success border border-success rounded-md px-4 py-2">
                    Accepted
                </x-fonts.paragraph>
            </div>
            @endif
        </x-accordion>
    </x-container>
</x-app-layout>