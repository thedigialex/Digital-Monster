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
    <x-alerts.error :message="session('error')" />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Website Policy</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                To use this site, you must agree to our Website Policy.
            </x-fonts.paragraph>
        </x-slot>
        <x-accordion title="Website Policy" :open="true" :icon="'fa fa-file-contract'">
            <x-fonts.paragraph><strong>Effective Date:</strong> 4/6/2025</x-fonts.paragraph>

            <div class="py-4"> <x-fonts.sub-header>Information We Collect</x-fonts.sub-header>
                <x-fonts.paragraph>When you create an account, we collect the following information:</x-fonts.paragraph>
                <ul class="p-4">
                    <li><x-fonts.paragraph>Name</x-fonts.paragraph></li>
                    <li><x-fonts.paragraph>Email address</x-fonts.paragraph></li>
                    <li><x-fonts.paragraph>Password</x-fonts.paragraph></li>
                </ul>
                <x-fonts.paragraph>We use this information to create and manage your account, and to provide you with access to our website.</x-fonts.paragraph>
                <x-fonts.paragraph>We will never sell or provide any outside access to your account data.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Analytics</x-fonts.sub-header>
                <x-fonts.paragraph>We use Google Analytics to collect information about how visitors use our site. This includes data such as pages visited, time spent on the site, and device/browser type. This information is collected anonymously and helps us improve the user experience.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Data Deletion</x-fonts.sub-header>
                <x-fonts.paragraph>You can delete your account and all associated data at any time by visiting your Profile Page. Once deleted, your personal data is permanently removed from our system.</x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>User Behavior and Account Suspension</x-fonts.sub-header>
                <x-fonts.paragraph>
                    We are committed to maintaining a safe, respectful, and welcoming community. If you engage in inappropriate behavior—such as harassment, spamming, or abusive conduct—your account may be locked. Accounts may be locked up to three times for repeated inappropriate behavior. Upon the third lock, your account will be permanently terminated. We reserve the right to interpret and enforce these guidelines as needed.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Changes to This Policy</x-fonts.sub-header>
                <x-fonts.paragraph>
                    We may update this Website Policy from time to time. Any changes will be posted on this page with an updated effective date.
                    When the policy is updated, you will be required to review and agree to the new version before continuing to use the site.
                </x-fonts.paragraph>
            </div>

            <div class="py-4">
                <x-fonts.sub-header>Contact Us</x-fonts.sub-header>
                <x-fonts.paragraph>If you have any questions about this Website Policy, feel free to contact us at <a href="mailto:thedigialex@gmail.com">thedigialex@gmail.com</a>.</x-fonts.paragraph>
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
                    <x-buttons.primary type="submit" label="Update" icon="fa-file-signature" />
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