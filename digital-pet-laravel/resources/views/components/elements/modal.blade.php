<div class="fixed inset-0 flex items-center justify-center">
    <div class="bg-white p-4 rounded-lg shadow-lg">
        <div class="flex justify-between items-center">
            <x-fonts.sub-header>
                {{ $title }}
            </x-fonts.sub-header>
            <button @click="open = false">Close</button>

        </div>
        <div class="p-4">
            {{ $slot }}
        </div>
    </div>
</div>
