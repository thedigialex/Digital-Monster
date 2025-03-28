<x-app-layout>
    <x-slot name="header">
        <x-fonts.sub-header>
            Shop
        </x-fonts.sub-header>
    </x-slot>

    <x-container>
        <x-slot name="header">
            <x-fonts.sub-header>
                Shop
            </x-fonts.sub-header>
            <x-fonts.paragraph id="user-balance">
                Balance: ¥ <span>{{ $user->bits }}</span>
            </x-fonts.paragraph>
        </x-slot>

        <x-container.background id="setup-section" :background="$background">
            <x-alerts.spinner id="loading-section"/>
            @if ($items->isEmpty())
            <x-fonts.paragraph class="text-text p-2 bg-primary rounded-md">Shop is sold out</x-fonts.paragraph>
            @else
            <div id="item-section" class="w-full md:w-1/2 bg-primary rounded-md overflow-auto my-4">
                @foreach ($items as $type => $groupedItems)
                <div class="category-container p-4" id="category-{{ $type }}">
                    <x-fonts.sub-header class="border-b-2 border-accent mb-4">{{ $type }}</x-fonts.sub-header>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach ($groupedItems as $item)
                        <div class="buyItem flex flex-col items-center w-32 p-2 bg-secondary border-2 border-accent rounded-md cursor-pointer"
                            id="item-{{ $item->id }}" data-item='{{ json_encode($item) }}' data-category="category-{{ $type}}">
                            <div class="relative w-24 h-24 p-2 rounded-md bg-primary">
                                <div class="w-full h-full"
                                    style="background: url('/storage/{{ $item->image }}') no-repeat; background-size: cover; background-position: 0 0;">
                                </div>
                                <span class="absolute bottom-1 right-1 bg-accent text-text text-xs px-2 py-1 rounded-md">
                                    ¥ {{ $item->price }}
                                </span>
                            </div>
                            <x-fonts.paragraph> {{ $item->name }} </x-fonts.paragraph>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loadingSection = document.getElementById('loading-section');
        const itemSection = document.getElementById('item-section');
        document.querySelectorAll('.buyItem').forEach(div => {
            div.addEventListener('click', function() {
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
                            balanceElement.textContent = `Balance: ¥ ${result.newBalance}`;
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