<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Evolution Chart
        </x-fonts.sub-header>
        <a href="{{ route('digigarden') }}">
            <x-buttons.button type="edit" icon="fa-arrow-left" label="Back" />
        </a>
    </x-slot>
    <x-container class="p-2 md:p-4">
        <x-slot name="header">
            <x-fonts.sub-header class="text-accent">Evolution Chart</x-fonts.sub-header>
        </x-slot>
        <x-slot name="info">
            <x-fonts.paragraph>
                This is the Evolution Chart page, where you can view all currently obtained monsters. Each monster's evolutionary path is displayed here. Clicking on a monster image will cycle through its different types, allowing you to explore the various forms and variations it can evolve into.
            </x-fonts.paragraph>
        </x-slot>
        @foreach ($monsters as $eggGroupId => $group)
        <x-accordion title="{{ $group->first()->eggGroup->name }}" :open="false" :icon="$fieldIconMap[$group->first()->eggGroup->field]">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col gap-8 items-center">
                    @foreach ($group->take(3) as $member)
                    <div class="flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md text-text">
                        <div class="w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center">
                            @if ($obtainedMonsterIds->contains($member->id))
                            <img src="{{ asset('storage/' . $member->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
                            @else
                            <i class="fas fa-question text-3xl text-accent"></i>
                            @endif
                        </div>
                        <x-fonts.paragraph>{{ $member->name }}</x-fonts.paragraph>
                    </div>
                    @endforeach
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    @foreach ($group->slice(3, 2) as $member)
                    <div class="w-full lg:w-1/2 flex justify-center">
                        <x-container.monster-card :dataMonster="$member" :obtained="$obtainedMonsterIds->contains($member->id)" />
                    </div>
                    @endforeach
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    @foreach ($group->slice(5, 4) as $member)
                    <div class="w-full lg:w-1/2 flex justify-center">
                        <x-container.monster-card :dataMonster="$member" :obtained="$obtainedMonsterIds->contains($member->id)" />
                    </div>
                    @endforeach
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    @foreach ($group->slice(9, 8) as $member)
                    <div class="w-full lg:w-1/2 flex justify-center">
                        <x-container.monster-card :dataMonster="$member" :obtained="$obtainedMonsterIds->contains($member->id)" />
                    </div>
                    @endforeach
                </div>
                <div class="flex flex-col lg:flex-row gap-4">
                    @foreach ($group->slice(17, 8) as $member)
                    <div class="w-full lg:w-1/2 flex justify-center">
                        <x-container.monster-card :dataMonster="$member" :obtained="$obtainedMonsterIds->contains($member->id)" />
                    </div>
                    @endforeach
                </div>
            </div>
        </x-accordion>
        
        @endforeach
    </x-container>
</x-app-layout>