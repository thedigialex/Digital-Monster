<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($event) ? 'Update Event' : 'Create Event' }}
        </x-fonts.sub-header>
        <a href="{{ route('location.edit') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">
                {{ isset($event) ? 'Update Event' : 'Create Event' }}
            </x-fonts.sub-header>
            @if (isset($event))
            <x-forms.delete-form :action="route('event.destroy')" label="Event" />
            @endif
        </x-slot>

        <form action="{{ route('event.update') }}" method="POST">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4 w-full">
                    <x-inputs.text
                        name="message"
                        class="w-full"
                        value="{{ old('message', isset($event) ? $event->message : '') }}"
                        :messages="$errors->get('message')" />
                    <x-inputs.dropdown
                        name="type"
                        class="w-full md:w-1/3"
                        onchange="toggleTypeDropdown(event)"
                        :options="$types"
                        useOptionKey="true"
                        :value="old('type', isset($event) ? $event->type : '')"
                        :messages="$errors->get('type')" />
                    <x-inputs.dropdown
                        name="item_id"
                        class="w-full md:w-1/3"
                        :options="$items"
                        useOptionKey="true"
                        :value="old('item_id', isset($event) ? $event->item_id : '')"
                        :messages="$errors->get('item_id')" />
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary label="{{ isset($event) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let dropdown = document.getElementById("type");
        if (dropdown.value !== '1') {
            document.getElementById('item_id_div').classList.add('hidden');
        }
    });

    function toggleTypeDropdown(event) {
        document.getElementById('item_id_div').classList.toggle('hidden', event.target.value !== '1');
    }
</script>