<div x-data="{ open: @json($open) }" class="py-4">
    <button
        @click="open = !open"
        class="w-full flex items-center p-2 bg-accent rounded-md text-secondary"
        x-bind:class="{'rounded-b-md': !open, 'rounded-b-none': open}">
        <span class="mx-2 fa fa-angle-down" x-show="!open"></span>
        <span class="mx-2 fa fa-angle-up" x-show="open"></span>
        @if(!empty($icon))
        <span class="mx-2 fa {{ $icon }}"></span>
        @endif
        <x-fonts.accent-header class="text-secondary"> {{ $title }}</x-fonts.accent-header>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition-all duration-200"
        x-transition:enter-start="max-h-0 opacity-0"
        x-transition:enter-end="max-h-screen opacity-100"
        x-transition:leave="transition-all duration-200"
        x-transition:leave-start="max-h-screen opacity-100"
        x-transition:leave-end="max-h-0 opacity-0"
        class="w-full bg-secondary p-4 rounded-b-md overflow-hidden origin-top">
        {{ $slot }}
    </div>
</div>