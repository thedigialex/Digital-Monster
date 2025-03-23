<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($monster) ? 'Update Monster' : 'Create Monster' }}
        </x-fonts.sub-header>
        <a href="{{ route('monsters.index') }}">
            <x-buttons.primary icon="fa-arrow-left" label="Go Back" />
        </a>
    </x-slot>

    @if ($errors->any())
    <x-alerts.error />
    @endif

    <x-container class="p-4">
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header class="text-accent">
                    {{ isset($monster) ? 'Update Monster' : 'Create Monster' }}
                </x-fonts.sub-header>
                @if (isset($monster))
                <x-forms.delete-form :action="route('monster.destroy')" label="Monster" />
                @endif
            </div>
        </x-slot>

        <form action="{{ route('monster.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-x-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.file label="Primary Image" name="image_0" :currentImage="$monster->image_0 ?? null" :messages="$errors->get('image_0')"/>
                        <x-inputs.file label="Secondary Image" name="image_1" :currentImage="$monster->image_1 ?? null" />
                        <x-inputs.file label="Tertiary Image" name="image_2" :currentImage="$monster->image_2 ?? null" />
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col md:flex-row gap-x-4 w-full">
                            <x-inputs.text name="name" divClasses="w-full" value="{{ $monster->name ?? '' }}" :messages="$errors->get('name')"/>
                            <div class="w-full lg:w-1/2 flex space-x-4">
                                <x-inputs.dropdown name="stage" divClasses="w-full lg:w-1/2" :options="$stages" :value="$monster->stage ?? ''" onchange="toggleElementFields()" :messages="$errors->get('stage')"/>
                                <x-inputs.dropdown name="egg_group_id" divClasses="w-full lg:w-1/2" :options="$eggGroups->pluck('name', 'id')->toArray()" useOptionKey="true" :value="$monster->egg_group_id ?? ''" :messages="$errors->get('egg_group_id')"/>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4 w-full">
                        <x-inputs.dropdown name="element_0" divClasses="w-full" :options="$elements" :value="$monster['element_0'] ?? ''" :messages="$errors->get('element_0')"/>
                            @foreach(range(1, 2) as $i)
                            <x-inputs.dropdown name="element_{{ $i }}" divClasses="w-full" :options="$elements" :value="$monster['element_'.$i] ?? ''" />
                            @endforeach
                        </div>
                        <div class="flex flex-col md:flex-row gap-x-4 w-full">
                            @foreach(['0', '1'] as $route)
                            <x-inputs.dropdown
                                name="route_{{ $route }}"
                                divClasses="w-full"
                                useOptionKey="true"
                                :options="$allMonsters->where('egg_group_id')->pluck('name', 'id')->toArray()"
                                :value="$monster && $monster->evolution ? $monster->evolution->where('route_'.$route, '!=', null)->pluck('route_'.$route)->first() ?? '' : ''" />
                            @endforeach
                        </div>
                        
                    </x-container.single>
                </div>
                <div class="flex justify-center py-4 mt-4">
                    <x-buttons.primary label="{{ isset($monster) ? 'Update' : 'Create' }}" icon="fa-save" />
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>

<script>
    function toggleElementFields() {
        const stageDropdown = document.getElementById('stage');
        const selectedStage = stageDropdown.value;
        const routeDivA = document.getElementById('route_0_div');
        const routeDivB = document.getElementById('route_1_div');
        const nonRouteElements = [
            'image_1_div',
            'image_2_div',
            'element_1_div',
            'element_2_div',
        ];

        nonRouteElements.forEach(id => {
            const element = document.getElementById(id);
            element.classList.add('hidden');
        });

        routeDivB.classList.add("hidden");
        routeDivA.classList.remove("hidden");

        if (
            stageDropdown.value != "Egg" &&
            stageDropdown.value != "Fresh" &&
            stageDropdown.value != "Child"
        ) {
            nonRouteElements.forEach(id => {
                const element = document.getElementById(id);
                element.classList.remove('hidden');
            });
            routeDivB.classList.remove("hidden");
            if (stageDropdown.value == "Mega") {
                routeDivA.classList.add("hidden");
                routeDivB.classList.add("hidden");
            }
        }
    }

    window.onload = function() {
        toggleElementFields();
    };
</script>