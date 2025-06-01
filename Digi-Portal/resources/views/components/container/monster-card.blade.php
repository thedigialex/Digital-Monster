@props(['monster', 'obtained'])

<div class="flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md text-text"
     x-data="{
        images: [
            '{{ asset('storage/' . $monster->image_0) }}',
            '{{ asset('storage/' . $monster->image_1) }}',
            '{{ asset('storage/' . $monster->image_2) }}'
        ].filter(i => i),
        currentIndex: 0,
        cycleImage() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        }
     }">
    <div class="w-24 h-24 p-2 rounded-md bg-primary flex items-center justify-center">
        @if ($obtained)
            <img
                :src="images[currentIndex]"
                alt="Monster Image"
                class="w-full h-full object-cover cursor-pointer"
                style="object-position: 0 0;"
                @click="cycleImage()" />
        @else
            <i class="fas fa-question text-3xl text-accent"></i>
        @endif
    </div>
    <x-fonts.paragraph>{{ $monster->name }}</x-fonts.paragraph>
</div>
