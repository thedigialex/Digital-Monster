<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>Users</x-fonts.sub-header>
    </x-slot>

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Users</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                Welcome to the community! you'll find an index of users showcasing their unique digi gardens. Feel free to browse, learn about each user's monsters.
            </x-fonts.paragraph>
        </x-slot>

        @if ($friends->isNotEmpty())
        <x-accordion title="Friends" :open="false" icon="fa-user-group">
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($friends as $user)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $user->name }}
                            </x-fonts.paragraph>
                        </x-table.data>

                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end gap-4">
                                <x-buttons.session model="other_user" :id="$user->id" route="digigarden.user" label="Garden" icon="fa-hard-drive" />
                                @if ($isAdmin)
                                <x-buttons.session model="user_edit" :id="$user->id" route="user.profile" label="View" icon="fa-eye" />
                                @endif
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </x-accordion>
        @endif
        <x-accordion title="Users" :open="false" icon="fa-globe">
            @if ($users->isNotEmpty())
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $user->name }}
                            </x-fonts.paragraph>
                        </x-table.data>

                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end gap-4">
                                <x-buttons.button type="edit" class="add_friend_button" label="Add" icon="fa-plus" :data-user="$user->id" />
                                <x-buttons.session model="other_user" :id="$user->id" route="digigarden.user" label="Garden" icon="fa-hard-drive" />
                                @if ($isAdmin)
                                <x-buttons.session model="user_edit" :id="$user->id" route="user.profile" label="View" icon="fa-eye" />
                                @endif
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
            @else
            <x-fonts.paragraph class="text-text p-4">No Users.</x-fonts.paragraph>
            @endif
        </x-accordion>
        @if ($requestedFriends->isNotEmpty())
        <x-accordion title="Requested" :open="false" icon="fa-hourglass-half">
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requestedFriends as $user)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $user->name }}
                            </x-fonts.paragraph>
                        </x-table.data>

                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end gap-4">
                                <x-buttons.button type="edit" class="cancel_friend_button" label="Cancel" icon="fa-xmark" :data-user="$user->id" />
                                <x-buttons.session model="other_user" :id="$user->id" route="digigarden.user" label="Garden" icon="fa-hard-drive" />
                                @if ($isAdmin)
                                <x-buttons.session model="user_edit" :id="$user->id" route="user.profile" label="View" icon="fa-eye" />
                                @endif
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </x-accordion>
        @endif
        @if ($pendingFriends->isNotEmpty())
        <x-accordion title="Pending" :open="false" icon="fa-plus">
            <x-table.table>
                <thead class="bg-primary">
                    <tr>
                        <x-table.header class="w-1/2 md:w-1/3 text-left">Name</x-table.header>
                        <x-table.header class="w-1/2 md:w-1/3"></x-table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingFriends as $user)
                    <tr class="{{ $loop->even ? 'bg-neutral' : 'bg-secondary' }}">
                        <x-table.data class="w-1/2 md:w-1/3">
                            <x-fonts.paragraph class="font-bold text-accent">
                                {{ $user->name }}
                            </x-fonts.paragraph>
                        </x-table.data>

                        <x-table.data class="w-1/2 md:w-1/3">
                            <div class="flex justify-end gap-4">
                                <x-buttons.button type="edit" class="add_friend_button" label="Accpet" icon="fa-plus" :data-user="$user->id" />
                                <x-buttons.button type="edit" class="cancel_friend_button" label="Deny" icon="fa-xmark" :data-user="$user->id" />
                                <x-buttons.session model="other_user" :id="$user->id" route="digigarden.user" label="Garden" icon="fa-hard-drive" />
                                @if ($isAdmin)
                                <x-buttons.session model="user_edit" :id="$user->id" route="user.profile" label="View" icon="fa-eye" />
                                @endif
                            </div>
                        </x-table.data>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.table>
        </x-accordion>
        @endif
    </x-container>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.add_friend_button').forEach(button => {
            button.addEventListener('click', function() {
                const user_id = this.getAttribute('data-user');
                const data = {
                    user_id: user_id,
                };
                this.remove();
                fetch("{{ route('users.friend.add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify(data)
                });
            });
        });
        document.querySelectorAll('.cancel_friend_button').forEach(button => {
            button.addEventListener('click', function() {
                const user_id = this.getAttribute('data-user');
                const data = {
                    user_id: user_id,
                };
                this.remove();
                fetch("{{ route('users.friend.cancel') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify(data)
                });
            });
        });
    });
</script>