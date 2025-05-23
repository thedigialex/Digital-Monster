<section>
    <header>
        <x-fonts.accent-header>Delete Account</x-fonts.accent-header>
        <x-fonts.paragraph>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</x-fonts.paragraph>
    </header>

    <x-container.modal name="confirm-user-deletion" title="Delete User Account">
        <x-slot name="button">
            <div class="flex items-center justify-center my-4">
                <x-buttons.button type="delete" @click="open = true" label="Delete" />
            </div>
        </x-slot>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <x-fonts.accent-header>Are you sure you want to delete your account?</x-fonts.accent-header>
            <x-fonts.paragraph>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</x-fonts.paragraph>

            <x-inputs.text name="password" type="password" divClasses="w-full" value="" :messages="$errors->userDeletion->get('password')" />

            <div class="flex items-center justify-center my-4">
                <x-buttons.button type="delete" label="Confirm" icon="fa-trash" />
            </div>

        </form>
    </x-container.modal>
</section>