<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row justify-between items-center">
            <x-fonts.sub-header>
                Shop
            </x-fonts.sub-header>
        </div>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <x-fonts.sub-header>
                    Shop
                </x-fonts.sub-header>
                <x-fonts.paragraph>
                    $ {{ $user->bits }}
                </x-fonts.paragraph>
            </div>
        </x-slot>
        <div class="flex flex-col justify-center overflow-y-auto bg-cover gap-4 bg-center h-[60vh] py-4"
            style="background-image: url('{{ asset($background) }}');">
            @foreach ($items as $type => $groupedItems)
            <div class="w-full md:w-1/2 mx-auto bg-secondary p-4 rounded-md">
                <x-fonts.sub-header class="border-b-2 border-accent mb-4">{{ $type }}</x-fonts.sub-header>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach ($groupedItems as $item)
                    <div class="flex flex-col items-center w-28 p-2 bg-secondary border-2 border-accent rounded-md">
                        <div class="relative w-24 h-24 p-2 rounded-md bg-primary">
                            <button class="buyItem w-full h-full"
                                data-item='{{ json_encode($item) }}'
                                style="background: url('/storage/{{ $item->image }}') no-repeat; background-size: cover; background-position: 0 0;">
                            </button>
                            <span class="absolute bottom-1 right-1 bg-accent text-text text-xs px-2 py-1 rounded-md">
                                ${{ $item->price }}
                            </span>
                        </div>
                        <x-fonts.paragraph> {{ $item->name }} </x-fonts.paragraph>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.buyItem').forEach(button => {
            button.addEventListener('click', function() {
                item = JSON.parse(this.getAttribute('data-item'));

                const data = {
                    item_id: item.id
                };

                fetch("{{ route('shop.buy') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(data)
                    }).then(response => response.json())
                    .then(result => {
                        console.log(result);
                    });
            });
        });

    });
</script>