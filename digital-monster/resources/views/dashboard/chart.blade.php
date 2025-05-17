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
        <h2 class="text-lg font-bold my-4">{{ $group->first()->eggGroup->name ?? 'Unknown Egg Group' }}</h2>

        <div class="flex flex-col gap-2 mb-4">
            @for ($i = 0; $i < 3; $i++)
                @if (isset($group[$i]))
                <div class="w-16 h-16 overflow-hidden">
                <img src="{{ asset('storage/' . $group[$i]->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
        </div>
        @endif
        @endfor
        </div>

        <div class="flex flex-row gap-2 mb-4">
            @for ($i = 3; $i < 5; $i++)
                @if (isset($group[$i]))
                <div class="w-16 h-16 overflow-hidden">
                <img src="{{ asset('storage/' . $group[$i]->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
        </div>
        @endif
        @endfor
        </div>

        <div class="flex flex-row gap-2 mb-4">
            @for ($i = 5; $i < 9; $i++)
                @if (isset($group[$i]))
                <div class="w-16 h-16 overflow-hidden">
                <img src="{{ asset('storage/' . $group[$i]->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
        </div>
        @endif
        @endfor
        </div>

        <div class="flex flex-row gap-2 mb-4">
            @for ($i = 9; $i < 17; $i++)
                @if (isset($group[$i]))
                <div class="w-16 h-16 overflow-hidden">
                <img src="{{ asset('storage/' . $group[$i]->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;">
        </div>
        @endif
        @endfor
        </div>

        <div class="flex gap-2">
            @for ($i = 17; $i <= 24; $i++)
                @if (isset($group[$i]))
                <div class="w-16 h-16 overflow-hidden">
                <img src="{{ asset('storage/' . $group[$i]->image_0) }}" alt="Monster Image" class="w-full h-full object-cover" style="object-position: 0 0;" />
        </div>
        @endif
        @endfor
        </div>
        @endforeach
    </x-container>
</x-app-layout>