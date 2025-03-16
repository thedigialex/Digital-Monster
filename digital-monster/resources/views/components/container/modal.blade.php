@props(['title' => 'Modal Title'])
<div x-data="{ open: false }">
    <div class="my-4">
        {{ $button }}
        <div
            x-show="open"
            class="fixed inset-0 flex items-center justify-center bg-neutral p-2 bg-opacity-50 z-10"
            x-transition
            @click.away="open = false"
            @keydown.escape.window="open = false">

            <div class="bg-primary border-2 border-accent rounded-md shadow-lg p-4 w-full max-w-md">
                <div class="flex items-center justify-between mb-4">
                    <x-fonts.accent-header>{{ $title }}</x-fonts.accent-header>
                    <x-buttons.primary @click="open = false" label="Close" icon="fa-x" />
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>