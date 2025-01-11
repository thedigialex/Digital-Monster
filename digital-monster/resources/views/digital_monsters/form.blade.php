<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($digitalMonster) ? 'Update Digital Monster' : 'Create Digital Monster' }}
            </x-fonts.sub-header>
            <a href="{{ route('digital_monsters.index') }}" class="mt-4 lg:mt-0">
                <x-primary-button>
                    Go Back <i class="fa fa-arrow-left ml-2"></i>
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-container>
        <form action="{{ route('digital_monsters.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @if (isset($digitalMonster))
            <input type="hidden" name="id" value="{{ $digitalMonster->id }}">
            @endif

            <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                <div class="w-full lg:w-1/4 mx-auto">
                    <div id="sprite_image_0_div">
                        <x-inputs.label for="sprite_image_0">Primary Image</x-inputs.label>
                        @if (isset($digitalMonster) && $digitalMonster->sprite_image_0)
                        <div class="mt-2 w-32 h-32 overflow-hidden mx-auto">
                            <img src="{{ asset('storage/' . $digitalMonster->sprite_image_0) }}" alt="Digital Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                        </div>
                        @endif
                        <x-inputs.text id="sprite_image_0" type="file" name="sprite_image_0" class="form-control w-full" accept="image/*" />
                    </div>
                    <div id="sprite_image_1_div" class="hidden">
                        <x-inputs.label for="sprite_image_1">Secondary Image</x-inputs.label>
                        @if (isset($digitalMonster) && $digitalMonster->sprite_image_1)
                        <div class="mt-2 w-32 h-32 overflow-hidden mx-auto">
                            <img src="{{ asset('storage/' . $digitalMonster->sprite_image_1) }}" alt="Digital Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                        </div>
                        @endif
                        <x-inputs.text id="sprite_image_1" type="file" name="sprite_image_1" class="form-control w-full mt-2" accept="image/*" />
                    </div>
                    <div id="sprite_image_2_div">
                        <x-inputs.label for="sprite_image_2">Tertiary Image</x-inputs.label>
                        @if (isset($digitalMonster) && $digitalMonster->sprite_image_2)
                        <div class="mt-2 w-32 h-32 overflow-hidden mx-auto">
                            <img src="{{ asset('storage/' . $digitalMonster->sprite_image_2) }}" alt="Digital Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                        </div>
                        @endif
                        <x-inputs.text id="sprite_image_2" type="file" name="sprite_image_2" class="form-control w-full mt-2" accept="image/*" />
                    </div>
                </div>
                <div class="flex-1 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="flex-1">
                            <x-inputs.label for="name">Name</x-inputs.label>
                            <x-inputs.text type="text" id="name" name="name" class="form-control w-full" value="{{ isset($digitalMonster) ? $digitalMonster->name : '' }}" required></x-inputs.text>
                        </div>
                        <div class="w-full lg:w-1/4">
                            <div class="flex space-x-4">
                                <div class="w-full lg:w-1/2">
                                    <x-inputs.label for="stage">Stage</x-inputs.label>
                                    <x-inputs.dropdown id="stage" name="stage" class="form-control w-full" required onchange="toggleElementFields()">
                                        <option value="" disabled {{ isset($digitalMonster) && !$digitalMonster->stage ? 'selected' : '' }}>Select Stage</option>
                                        @foreach($stages as $stage)
                                        <option value="{{ $stage }}" {{ isset($digitalMonster) && $digitalMonster->stage === $stage ? 'selected' : '' }}>
                                            {{ $stage }}
                                        </option>
                                        @endforeach
                                    </x-inputs.dropdown>
                                </div>
                                <div class="w-full lg:w-1/2">
                                    <x-inputs.label for="egg_group_id">Egg Group</x-inputs.label>
                                    <x-inputs.dropdown id="egg_group_id" name="egg_group_id" class="form-control w-full" required onchange="filterMonsters()">
                                        <option value="" disabled {{ isset($digitalMonster) && !$digitalMonster->egg_group_id ? 'selected' : '' }}>Select Egg</option>
                                        @foreach($eggGroups as $eggGroup)
                                        <option value="{{ $eggGroup->id }}" {{ isset($digitalMonster) && $digitalMonster->egg_group_id == $eggGroup->id ? 'selected' : '' }}>
                                            {{ $eggGroup->name }}
                                        </option>
                                        @endforeach
                                    </x-inputs.dropdown>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="w-full lg:w-1/3" id="element_div_0">
                            <x-inputs.label for="element_0">Primary Element</x-inputs.label>
                            <x-inputs.dropdown id="element_0" name="element_0" class="form-control w-full" required>
                                <option value="" disabled {{ isset($digitalMonster) && !$digitalMonster->element_0 ? 'selected' : '' }}>Select Primary Element</option>
                                @foreach($elements as $element)
                                <option value="{{ $element }}" {{ isset($digitalMonster) && (string)$digitalMonster->element_0 === $element ? 'selected' : '' }}>
                                    {{ $element }}
                                </option>
                                @endforeach
                            </x-inputs.dropdown>
                        </div>
                        <div class="w-full lg:w-1/3" id="element_div_1">
                            <x-inputs.label for="element_1">Secondary Element</x-inputs.label>
                            <x-inputs.dropdown id="element_1" name="element_1" class="form-control w-full" required>
                                <option value="" disabled {{ isset($digitalMonster) && !$digitalMonster->element_1 ? 'selected' : '' }}>Select Secondary Element</option>
                                @foreach($elements as $element)
                                <option value="{{ $element }}" {{ isset($digitalMonster) && (string)$digitalMonster->element_1 === $element ? 'selected' : '' }}>
                                    {{ $element }}
                                </option>
                                @endforeach
                            </x-inputs.dropdown>
                        </div>
                        <div class="w-full lg:w-1/3" id="element_div_2">
                            <x-inputs.label for="element_2">Tertiary Element</x-inputs.label>
                            <x-inputs.dropdown id="element_2" name="element_2" class="form-control w-full" required>
                                <option value="" disabled {{ isset($digitalMonster) && !$digitalMonster->element_2 ? 'selected' : '' }}>Select Tertiary Element</option>
                                @foreach($elements as $element)
                                <option value="{{ $element }}" {{ isset($digitalMonster) && (string)$digitalMonster->element_2 === $element ? 'selected' : '' }}>
                                    {{ $element }}
                                </option>
                                @endforeach
                            </x-inputs.dropdown>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:space-x-4 space-y-4 lg:space-y-0">
                        <div class="w-full lg:w-1/2" id="route_div_A">
                            <x-inputs.label for="route_a">Route A</x-inputs.label>
                            <x-inputs.dropdown id="route_a" name="route_a" class="form-control w-full">
                                <option value="" {{ !isset($digitalMonster) }}>None (no evolution)</option>
                                @foreach($allDigitalMonsters as $monster)
                                @if (!isset($digitalMonster) || $monster->id !== $digitalMonster->id)
                                <option value="{{ $monster->id }}" data-egg-group-id="{{ $monster->egg_group_id }}"
                                    {{ isset($digitalMonster) && $digitalMonster->evolutionToRoutes->contains('route_a', $monster->id) ? 'selected' : '' }}>
                                    Stage: {{ $monster->stage }} Name: {{ $monster->name }}
                                </option>
                                @endif
                                @endforeach
                            </x-inputs.dropdown>
                        </div>
                        <div class="w-full lg:w-1/2" id="route_div_B">
                            <x-inputs.label for="route_b">Route B</x-inputs.label>
                            <x-inputs.dropdown id="route_b" name="route_b" class="form-control w-full">
                                <option value="" {{ !isset($digitalMonster) }}>None (no evolution)</option>
                                @foreach($allDigitalMonsters as $monster)
                                @if (!isset($digitalMonster) || $monster->id !== $digitalMonster->id)
                                <option value="{{ $monster->id }}" data-egg-group-id="{{ $monster->egg_group_id }}"
                                    {{ isset($digitalMonster) && $digitalMonster->evolutionToRoutes->contains('route_b', $monster->id) ? 'selected' : '' }}>
                                    Stage: {{ $monster->stage }} Name: {{ $monster->name }}
                                </option>
                                @endif
                                @endforeach
                            </x-inputs.dropdown>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($digitalMonster) ? 'Update' : 'Create' }} <i class="fa fa-save ml-2"></i></x-primary-button>
            </div>
        </form>

    </x-container>
</x-app-layout>

<script>
    function toggleElementFields() {
        const stageDropdown = document.getElementById('stage');
        const selectedStage = stageDropdown.value;
        const routeDivA = document.getElementById('route_div_A');
        const routeDivB = document.getElementById('route_div_B');
        const nonRouteElements = [
            'sprite_image_1_div',
            'sprite_image_2_div',
            'element_div_1',
            'element_div_2'
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

    function filterMonsters(onLoad = false) {
        const eggGroupId = document.getElementById('egg_group_id').value;
        const routeADropdown = document.getElementById('route_a');
        const routeBDropdown = document.getElementById('route_b');
        const allMonstersA = routeADropdown.querySelectorAll('option[data-egg-group-id]');
        const allMonstersB = routeBDropdown.querySelectorAll('option[data-egg-group-id]');
        if (!onLoad) {
            routeADropdown.value = "";
            routeBDropdown.value = ""; 
        }
        const filterOptions = (options) => {
            options.forEach(option => {
                if (option.value === "" || option.getAttribute('data-egg-group-id') === eggGroupId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        };

        filterOptions(allMonstersA);
        filterOptions(allMonstersB);
    }

    window.onload = function() {
        filterMonsters(true);
        toggleElementFields();
    };
</script>