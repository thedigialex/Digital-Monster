<div x-data="{ open: @json($open) }">
    <button
        @click="open = !open"
        class="w-full flex justify-between items-center p-4 bg-accent rounded-lg"
        x-bind:class="{'rounded-b-lg': !open, 'rounded-b-none border-b-4 border-primary': open}">
        <x-fonts.accent-header class="text-primary"> {{ $title }}</x-fonts.accent-header>
        <span class="text-primary" x-show="!open">▼</span>
        <span class="text-primary" x-show="open">▲</span>
    </button>

    <div
        x-show="open"
        class="w-full bg-secondary p-4 rounded-b-lg transition-all duration-300"
        x-collapse>
        {{ $slot }}
    </div>
</div>