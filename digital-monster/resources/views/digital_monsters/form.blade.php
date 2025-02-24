<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            {{ isset($digitalMonster) ? 'Update Digital Monster' : 'Create Digital Monster' }}
        </x-fonts.sub-header>
        <a href="{{ route('digital_monsters.index') }}">
            <x-primary-button icon="fa-arrow-left">
                Go Back
            </x-primary-button>
        </a>
    </x-slot>

    @if ($errors->any())
    <x-slot name="alert">
        <x-alerts.error>
            Saving data, please fix fields.
        </x-alerts.error>
    </x-slot>
    @endif

    <x-container class="p-4">
        <div class="flex justify-end w-full">
            @if (isset($digitalMonster))
            <form action="{{ route('digital_monsters.destroy', $digitalMonster->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this digital monster?');">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit" icon="fa-trash">
                    Delete
                </x-danger-button>
            </form>
            @endif
        </div>

        <form action="{{ route('digital_monsters.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($digitalMonster))
            <input type="hidden" name="id" value="{{ $digitalMonster->id }}">
            @endif
            <x-container.single>
                <div class="flex flex-col md:flex-row gap-4">
                    <x-container.single class="md:w-1/3 w-full">
                        <x-inputs.file label="Primary Image" name="sprite_image_0" :currentImage="$digitalMonster->sprite_image_0 ?? null" />
                        <x-inputs.file label="Secondary Image" name="sprite_image_1" :currentImage="$digitalMonster->sprite_image_1 ?? null" />
                        <x-inputs.file label="Tertiary Image" name="sprite_image_2" :currentImage="$digitalMonster->sprite_image_2 ?? null" />
                    </x-container.single>
                    <x-container.single class="md:w-2/3 w-full">
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            <x-inputs.text name="name" divClasses="w-full" value="{{ $digitalMonster->name ?? '' }}" required />
                            <div class="w-full lg:w-1/2 flex space-x-4">
                                <x-inputs.dropdown name="stage" divClasses="w-full lg:w-1/2" required :options="$stages" :value="$digitalMonster->stage ?? ''" onchange="toggleElementFields()" />
                                <x-inputs.dropdown name="egg_group_id" divClasses="w-full lg:w-1/2" required :options="$eggGroups->pluck('name', 'id')->toArray()" useOptionKey="true" :value="$digitalMonster->egg_group_id ?? ''" />
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            @foreach(range(0, 2) as $i)
                            <x-inputs.dropdown name="element_{{ $i }}" divClasses="w-full" :options="$elements" :value="$digitalMonster['element_'.$i] ?? ''" />
                            @endforeach
                        </div>
                        <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                            @foreach(['a', 'b'] as $route)
                            <x-inputs.dropdown
                                name="route_{{ $route }}"
                                divClasses="w-full"
                                :options="($digitalMonster && $digitalMonster->egg_group_id) ? $allDigitalMonsters->where('egg_group_id', $digitalMonster->egg_group_id)->pluck('name', 'id')->toArray() : []"
                                useOptionKey="true"
                                :value="$digitalMonster && $digitalMonster->evolutionToRoutes ? $digitalMonster->evolutionToRoutes->where('route_'.$route, '!=', null)->pluck('route_'.$route)->first() ?? '' : ''"
                                />
                                @endforeach
                        </div>
                    </x-container.single>
                </div>
                <div class="flex justify-center">
                    <x-primary-button type="submit" icon="fa-save">
                        {{ isset($digitalMonster) ? 'Update' : 'Create' }}
                    </x-primary-button>
                </div>
            </x-container.single>
        </form>
    </x-container>
</x-app-layout>

<script>
    function toggleElementFields() {
        const stageDropdown = document.getElementById('stage');
        const selectedStage = stageDropdown.value;
        const routeDivA = document.getElementById('route_a_div');
        const routeDivB = document.getElementById('route_b_div');
        const nonRouteElements = [
            'sprite_image_1_div',
            'sprite_image_2_div',
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