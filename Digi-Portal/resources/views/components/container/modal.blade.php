@props(['title' => 'Modal Title'])

<div x-data="{ open: false }" x-cloak>
    {{ $button }}
    <div
        x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-neutral p-2 bg-opacity-50 z-10"
        x-transition
        @click.away="open = false"
        @keydown.escape.window="open = false">

        <div class="bg-primary rounded-md shadow-lg w-full max-w-lg">
            <div class="flex items-center justify-between p-4">
                <x-fonts.accent-header>{{ $title }}</x-fonts.accent-header>
                <x-buttons.button type="edit" label="Close" icon="fa-x" @click="open = false"/>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>