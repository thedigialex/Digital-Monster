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
                Balance: $ <span>{{ $user->bits }}</span>
            </x-fonts.paragraph>
        </x-slot>
        <x-container.background :background="$background" :timeOfDay="$timeOfDay">
            <x-alerts.spinner id="loading-section" />
            <x-fonts.paragraph id="messageBox" class="hidden text-text p-4 bg-primary rounded-md"></x-fonts.paragraph>
            @if (!$items->isEmpty())
            <div id="item-section" class="flex flex-col items-center gap-4 w-full">
                @foreach ($items as $type => $groupedItems)
                <div class="flex flex-col transition-transform duration-300 w-3/4 lg:w-1/3 gap-4 overflow-hidden bg-primary p-4 rounded-md" id="category-{{ $type }}">
                    <x-fonts.sub-header class="border-b-2 border-accent p-4">{{ $type }}</x-fonts.sub-header>
                    <div class="flex flex-wrap justify-center gap-4 my-4">
                        @foreach ($groupedItems as $item)
                        <x-container.item-card :data-item="$item" buttonClass="buyItem" :isUserItem="false" :bottomText="'$ ' . $item->price" />
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <x-fonts.paragraph class="text-text p-4 bg-primary rounded-md">Shop is sold out</x-fonts.paragraph>
            @endif
        </x-container.background>
    </x-container>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loadingSection = document.getElementById('loading-section');
        const itemSection = document.getElementById('item-section');
        const messageBox = document.getElementById('messageBox');
        document.querySelectorAll('.buyItem').forEach(div => {
            div.addEventListener('click', function() {
                loadingSection.classList.remove("hidden");
                itemSection.classList.add("hidden");
                messageBox.classList.add("hidden");
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
                        } else {
                            messageBox.textContent = result.message;
                            messageBox.classList.remove("hidden");
                        }
                    });
            });
        });
    });
</script>