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
                <x-fonts.paragraph id="user-balance">
                    $ {{ $user->bits }}
                </x-fonts.paragraph>

            </div>
        </x-slot>
        <div class="flex flex-col justify-center overflow-y-auto bg-cover gap-4 bg-center h-[60vh] py-4"
            style="background-image: url('{{ asset($background) }}');">
            <div id=loading-section class="hidden flex justify-center items-center">
                <div class="relative w-24 h-24">
                    <div class="absolute inset-0 border-8 border-transparent border-t-secondary rounded-full animate-spin"></div>
                </div>
            </div>
            <div id="item-section">
                @if ($items->isEmpty())
                <div class="flex justify-center items-center">
                    <x-fonts.paragraph id="status-text" class="text-text p-2 bg-primary rounded-md">Shop Sold Out</x-fonts.paragraph>
                </div>
                @else
                @foreach ($items as $type => $groupedItems)
                <div class="category-container w-full md:w-1/2 mx-auto bg-secondary p-4 rounded-md" id="category-{{ Str::slug($type) }}">
                    <x-fonts.sub-header class="border-b-2 border-accent mb-4">{{ $type }}</x-fonts.sub-header>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach ($groupedItems as $item)
                        <div class="item-container flex flex-col items-center w-28 p-2 bg-secondary border-2 border-accent rounded-md"
                            id="item-{{ $item->id }}" data-category="category-{{ Str::slug($type) }}">
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
                @endif
            </div>

        </div>

    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loadingSection = document.getElementById('loading-section');
        const itemSection = document.getElementById('item-section');
        document.querySelectorAll('.buyItem').forEach(button => {
            button.addEventListener('click', function() {
                loadingSection.classList.remove("hidden");
                itemSection.classList.add("hidden");
                let item = JSON.parse(this.getAttribute('data-item'));

                fetch("{{ route('shop.buy') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            item_id: item.id
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        itemSection.classList.remove("hidden");
                        loadingSection.classList.add("hidden");
                        if (result.successful) {
                            let balanceElement = document.querySelector('#user-balance');
                            balanceElement.textContent = `$ ${result.newBalance}`;
                            if (result.removeItem) {
                                let itemContainer = document.getElementById(`item-${item.id}`);
                                let categoryId = itemContainer.getAttribute('data-category');
                                itemContainer.remove();

                                let categoryContainer = document.getElementById(categoryId);
                                let remainingItems = categoryContainer.querySelectorAll('.item-container');
                                if (remainingItems.length == 0) {
                                    categoryContainer.remove();
                                }
                            }
                        }
                    });
            });
        });
    });
</script>