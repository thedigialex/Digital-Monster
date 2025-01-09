<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ isset($digitalMonster) ? 'Update Digital Monster' : 'Create Digital Monster' }}
            </x-fonts.sub-header>
            <a href="{{ route('digital_monsters.index') }}">
                <x-primary-button>
                    Go Back
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
            <div class="flex-1">
                <x-input-label for="name">Name</x-input-label>
                <x-text-input type="text" name="name" class="form-control w-full" value="{{ isset($digitalMonster) ? $digitalMonster->name : '' }}" required></x-text-input>
            </div>
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/3 px-2">
                    <x-input-label for="egg_group_id">Egg Group</x-input-label>
                    <x-text-dropdown id="egg_group_id" name="egg_group_id" class="form-control w-full" required onchange="filterMonsters()">
                        <option value="" disabled {{ !isset($digitalMonster) ? 'selected' : '' }}>Select Egg</option>
                        @foreach($eggGroups as $eggGroup)
                        <option value="{{ $eggGroup->id }}" {{ isset($digitalMonster) && $digitalMonster->egg_group_id == $eggGroup->id ? 'selected' : '' }}>
                            {{ $eggGroup->name }}
                        </option>
                        @endforeach
                    </x-text-dropdown>
                </div>

                <div class="w-full md:w-1/3 px-2">
                    <x-input-label for="stage">Stage</x-input-label>
                    <x-text-dropdown id="stage" name="stage" class="form-control w-full" required onchange="toggleElementFields()">
                        <option value="" disabled {{ !isset($digitalMonster) ? 'selected' : '' }}>Select Stage</option>
                        @foreach($stages as $value => $label)
                        <option value="{{ $value }}" {{ isset($digitalMonster) && $digitalMonster->stage == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </x-text-dropdown>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-full md:w-1/3 px-2">
                    <x-input-label for="element_0">Element 0</x-input-label>
                    <x-text-dropdown id="element_0" name="element_0" class="form-control w-full" required>
                        <option value="" disabled {{ !isset($digitalMonster) ? 'selected' : '' }}>Select Element 0</option>
                        @foreach($elements as $value => $label)
                        <option value="{{ $value }}" {{ isset($digitalMonster) && $digitalMonster->element_0 == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </x-text-dropdown>
                    <x-input-label for="sprite_image_0">Sprite Image 0</x-input-label>
                    <x-text-input id="sprite_image_0" name="sprite_image_0" type="file" class="form-control w-full" accept="image/*" />
                    @if (isset($digitalMonster) && $digitalMonster->sprite_image_0)
                    <img src="{{ asset('storage/' . $digitalMonster->sprite_image_0) }}" alt="Sprite Image 0" class="mt-2" width="100">
                    @endif
                </div>

                <div class="w-full md:w-1/3 px-2" id="element-fields-1" style="display: none;">
                    <x-input-label for="element_1">Element 1</x-input-label>
                    <x-text-dropdown id="element_1" name="element_1" class="form-control w-full">
                        <option value="" disabled {{ !isset($digitalMonster) ? 'selected' : '' }}>Select Element 1</option>
                        @foreach($elements as $value => $label)
                        <option value="{{ $value }}" {{ isset($digitalMonster) && $digitalMonster->element_1 == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </x-text-dropdown>
                    <x-input-label for="sprite_image_1">Sprite Image 1</x-input-label>
                    <x-text-input id="sprite_image_1" name="sprite_image_1" type="file" class="form-control w-full" accept="image/*" />
                    @if (isset($digitalMonster) && $digitalMonster->sprite_image_1)
                    <img src="{{ asset('storage/' . $digitalMonster->sprite_image_1) }}" alt="Sprite Image 1" class="mt-2" width="100">
                    @endif
                </div>

                <div class="w-full md:w-1/3 px-2" id="element-fields-2" style="display: none;">
                    <x-input-label for="element_2">Element 2</x-input-label>
                    <x-text-dropdown id="element_2" name="element_2" class="form-control w-full">
                        <option value="" disabled {{ !isset($digitalMonster) ? 'selected' : '' }}>Select Element 2</option>
                        @foreach($elements as $value => $label)
                        <option value="{{ $value }}" {{ isset($digitalMonster) && $digitalMonster->element_2 == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </x-text-dropdown>
                    <x-input-label for="sprite_image_2">Sprite Image 2</x-input-label>
                    <x-text-input id="sprite_image_2" name="sprite_image_2" type="file" class="form-control w-full" accept="image/*" />
                    @if (isset($digitalMonster) && $digitalMonster->sprite_image_2)
                    <img src="{{ asset('storage/' . $digitalMonster->sprite_image_2) }}" alt="Sprite Image 2" class="mt-2" width="100">
                    @endif
                </div>

            </div>

            <div class="flex flex-wrap">

                <div class="w-full md:w-1/3 px-2">
                    <x-input-label for="route_a">Route A</x-input-label>
                    <x-text-dropdown id="route_a" name="route_a" class="form-control w-full">
                        <option value="" {{ !isset($digitalMonster) ? 'selected' : '' }}>None (no evolution)</option>
                        @foreach($allDigitalMonsters as $monster)
                        @if (!isset($digitalMonster) || $monster->id !== $digitalMonster->id)
                        <option value="{{ $monster->id }}" data-egg-group-id="{{ $monster->egg_group_id }}"
                            {{ isset($digitalMonster) && $digitalMonster->evolutionToRoutes->contains('route_a', $monster->id) ? 'selected' : '' }}>
                            {{ $monster->name }} (ID: {{ $monster->id }})
                        </option>
                        @endif
                        @endforeach
                    </x-text-dropdown>
                </div>

                <div class="w-full md:w-1/3 px-2">
                    <x-input-label for="route_b">Route B</x-input-label>
                    <x-text-dropdown id="route_b" name="route_b" class="form-control w-full">
                        <option value="" {{ !isset($digitalMonster) ? 'selected' : '' }}>None (no evolution)</option>
                        @foreach($allDigitalMonsters as $monster)
                        @if (!isset($digitalMonster) || $monster->id !== $digitalMonster->id)
                        <option value="{{ $monster->id }}" data-egg-group-id="{{ $monster->egg_group_id }}"
                            {{ isset($digitalMonster) && $digitalMonster->evolutionToRoutes->contains('route_b', $monster->id) ? 'selected' : '' }}>
                            {{ $monster->name }} (ID: {{ $monster->id }})
                        </option>
                        @endif
                        @endforeach
                    </x-text-dropdown>
                </div>
            </div>

            <div class="flex justify-center">
                <x-primary-button type="submit">{{ isset($digitalMonster) ? 'Update' : 'Create' }}</x-primary-button>
            </div>

        </form>
    </x-container>
</x-app-layout>

<script>
    function toggleElementFields() {
        const stageDropdown = document.getElementById('stage');
        const selectedStage = stageDropdown.value;
        const elementField_1 = document.getElementById('element-fields-1');
        const elementField_2 = document.getElementById('element-fields-2');
        const stagesToShow = ['Rookie', 'Champion', 'Ultimate', 'Mega'];
        if (stagesToShow.includes(selectedStage)) {
            elementField_1.style.display = 'block';
            elementField_2.style.display = 'block';
        } else {
            elementField_1.style.display = 'none';
            elementField_2.style.display = 'none';
        }
    }

    function filterMonsters() {
        const eggGroupId = document.getElementById('egg_group_id').value;
        const routeADropdown = document.getElementById('route_a');
        const routeBDropdown = document.getElementById('route_b');
        const allMonstersA = routeADropdown.querySelectorAll('option[data-egg-group-id]');
        const allMonstersB = routeBDropdown.querySelectorAll('option[data-egg-group-id]');
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
        filterMonsters();
        toggleElementFields();
    };
</script>