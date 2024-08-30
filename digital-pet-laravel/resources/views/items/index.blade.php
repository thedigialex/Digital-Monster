<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ __('Items') }}
        </x-fonts.sub-header>
    </x-slot>
    <x-elements.container>
        <x-tables.items-table :itemTypes="$itemTypes" :items="$items" :route="route('items.handle')" />
        @if($items->isEmpty())
        <x-fonts.paragraph>
            {{ __('No items available at the moment.') }}
        </x-fonts.paragraph>
        @endif
    </x-elements.container>
</x-app-layout>