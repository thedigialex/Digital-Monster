@props(['label' => null, 'name' => 'file', 'accept' => 'image/*', 'currentImage' => null, 'messages' => ''])

<div id="{{ $name }}_div" class="flex flex-col mt-4">
    @if ($label)
    <x-inputs.label for="{{ $name }}" class="pb-1">{{ $label }}</x-inputs.label>
    @endif

    <div class="relative w-full">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $name }}"
            accept="{{ $accept }}"
            onchange="previewImage(event, '{{ $name }}')"
            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
        <input
            type="text"
            id="{{ $name }}_text"
            readonly
            placeholder="Choose a file..."
            class="w-full text-text bg-neutral rounded-md p-2 cursor-pointer @error($name) border-error @enderror"
            onclick=" document.getElementById('{{ $name }}').click()">
    </div>

    @if ($messages && is_array($messages) && count($messages) > 0)
    <p class="text-error text-sm mt-1">{{ implode(', ', $messages) }}</p>
    @endif

    <div class="flex justify-center items-center gap-4">
        <div id="{{ $name }}_preview_container" class="mt-2 w-32 h-32 overflow-hidden hidden border-2 border-accent bg-accent rounded flex flex-col items-center relative">
            <img id="{{ $name }}_preview" src="" class="w-full h-full object-cover" style="object-position: 0 0;" />
            <button type="button" onclick="removeImage('{{ $name }}')" class="absolute top-1 right-1 bg-error text-text rounded p-2 text-xs">✕</button>
        </div>

        @if ($currentImage)
        <div id="{{ $name }}_current_container" class="mt-2 w-32 h-32 overflow-hidden border-2 border-secondary bg-secondary rounded flex flex-col items-center">
            <img src="{{ asset('storage/' . $currentImage) }}" alt="Item Image" class="w-full h-full object-cover" style="object-position: 0 0;" />
        </div>
        @endif
    </div>

    <script>
        function previewImage(event, id) {
            const input = event.target;
            const preview = document.getElementById(id + '_preview');
            const previewContainer = document.getElementById(id + '_preview_container');
            const textInput = document.getElementById(id + '_text');
            const currentImageContainer = document.getElementById(id + '_current_container');

            if (input.files && input.files[0]) {
                textInput.value = input.files[0].name;
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    if (currentImageContainer) {
                        currentImageContainer.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
                if (currentImageContainer) {
                    currentImageContainer.classList.remove('hidden');
                }
            }
        }

        function removeImage(id) {
            const input = document.getElementById(id);
            const previewContainer = document.getElementById(id + '_preview_container');
            const textInput = document.getElementById(id + '_text');
            const currentImageContainer = document.getElementById(id + '_current_container');

            input.value = "";
            textInput.value = "";
            previewContainer.classList.add('hidden');
            if (currentImageContainer) {
                currentImageContainer.classList.remove('hidden');
            }
        }
    </script>
</div>