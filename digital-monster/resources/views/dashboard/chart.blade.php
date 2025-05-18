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
                This site lets you collect, evolve, and manage digital creatures. Start by registering an account to receive dataCrystals, which you can use to choose an egg from the DigiConverge page. Hatch and evolve your creatures in the DigiGarden, track their progress, and build your ultimate digital team!
            </x-fonts.paragraph>
        </x-slot>

        @foreach ($monsters as $eggGroupId => $group)
        <div class="flex flex-col items-center justify-between gap-8 mb-8">
            <div class="flex flex-col gap-8 items-center">
                <x-fonts.accent-header>Egg Group: {{ $group->first()->eggGroup->name }}</x-fonts.accent-header>
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
                <div class="flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md text-text"
                    x-data="{
                    images: [
                        '{{ asset('storage/' . $member->image_0) }}',
                        '{{ asset('storage/' . $member->image_1) }}',
                        '{{ asset('storage/' . $member->image_2) }}'
                    ].filter(i => i),
                    currentIndex: 0,
                    cycleImage() {
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    }
                }">
                    <div class="w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center">
                        @if ($obtainedMonsterIds->contains($member->id))
                        <img
                            :src="images[currentIndex]"
                            alt="Monster Image"
                            class="w-full h-full object-cover cursor-pointer"
                            style="object-position: 0 0;"
                            @click="cycleImage()">
                        @else
                        <i class="fas fa-question text-3xl text-accent"></i>
                        @endif
                    </div>
                    <x-fonts.paragraph>{{ $member->name }}</x-fonts.paragraph>
                </div>
                @endforeach
            </div>

            <div class="flex flex-col lg:flex-row gap-4">
                @foreach ($group->slice(5, 4) as $member)
                <div class="flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md text-text"
                    x-data="{
                    images: [
                        '{{ asset('storage/' . $member->image_0) }}',
                        '{{ asset('storage/' . $member->image_1) }}',
                        '{{ asset('storage/' . $member->image_2) }}'
                    ].filter(i => i),
                    currentIndex: 0,
                    cycleImage() {
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    }
                }">
                    <div class="w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center">
                        @if ($obtainedMonsterIds->contains($member->id))
                        <img
                            :src="images[currentIndex]"
                            alt="Monster Image"
                            class="w-full h-full object-cover cursor-pointer"
                            style="object-position: 0 0;"
                            @click="cycleImage()">
                        @else
                        <i class="fas fa-question text-3xl text-accent"></i>
                        @endif
                    </div>
                    <x-fonts.paragraph>{{ $member->name }}</x-fonts.paragraph>
                </div>
                @endforeach
            </div>

            <div class="flex flex-col lg:flex-row gap-4">
                @foreach ($group->slice(9, 8) as $member)
                <div class="flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md text-text"
                    x-data="{
                    images: [
                        '{{ asset('storage/' . $member->image_0) }}',
                        '{{ asset('storage/' . $member->image_1) }}',
                        '{{ asset('storage/' . $member->image_2) }}'
                    ].filter(i => i),
                    currentIndex: 0,
                    cycleImage() {
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    }
                }">
                    <div class="w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center">
                        @if ($obtainedMonsterIds->contains($member->id))
                        <img
                            :src="images[currentIndex]"
                            alt="Monster Image"
                            class="w-full h-full object-cover cursor-pointer"
                            style="object-position: 0 0;"
                            @click="cycleImage()">
                        @else
                        <i class="fas fa-question text-3xl text-accent"></i>
                        @endif
                    </div>
                    <x-fonts.paragraph>{{ $member->name }}</x-fonts.paragraph>
                </div>
                @endforeach
            </div>

            <div class="flex flex-col lg:flex-row gap-4">
                @foreach ($group->slice(17, 8) as $member)
                <div class="flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md text-text"
                    x-data="{
                    images: [
                        '{{ asset('storage/' . $member->image_0) }}',
                        '{{ asset('storage/' . $member->image_1) }}',
                        '{{ asset('storage/' . $member->image_2) }}'
                    ].filter(i => i),
                    currentIndex: 0,
                    cycleImage() {
                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    }
                }">
                    <div class="w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center">
                        @if ($obtainedMonsterIds->contains($member->id))
                        <img
                            :src="images[currentIndex]"
                            alt="Monster Image"
                            class="w-full h-full object-cover cursor-pointer"
                            style="object-position: 0 0;"
                            @click="cycleImage()">
                        @else
                        <i class="fas fa-question text-3xl text-accent"></i>
                        @endif
                    </div>
                    <x-fonts.paragraph>{{ $member->name }}</x-fonts.paragraph>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </x-container>
</x-app-layout>