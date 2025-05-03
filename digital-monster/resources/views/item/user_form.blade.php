<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($userItem) ? 'Update User Item' : 'Assign User Item' }}
        </x-fonts.sub-header>
        <a href="{{ route('user.profile') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.alert type="Error" />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($userItem) ? 'Update User Item' : 'Assign User Item' }}
            </x-fonts.sub-header>
            @if (isset($userItem))
            <x-forms.delete-form :action="route('user.item.destroy')" label="Item" />
            @endif
        </x-slot>

        <form action="{{ route('user.item.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-inputs.dropdown
                        name="item_id"
                        class="w-full"
                        :messages="$errors->get('item_id')"
                        :options="$allItems->pluck('name', 'id')->toArray()"
                        useOptionKey="true"
                        :data-items="$allItems->keyBy('id')->toArray()"
                        :value="old('item', isset($userItem) ? $userItem->item->id : '')" />
                    <x-inputs.text
                        name="quantity"
                        class="w-full"
                        :messages="$errors->get('quantity')"
                        type="number"
                        :value="old('quantity', isset($userItem) ? $userItem->quantity : 1)" />
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary label="{{ isset($userItem) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>