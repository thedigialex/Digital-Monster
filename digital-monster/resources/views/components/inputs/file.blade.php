@props(['label' => null, 'name' => 'file', 'accept' => 'image/*', 'currentImage' => null, 'messages' => ''])

<div id="{{ $name }}_div" {{ $attributes->merge(['class' => 'flex flex-col mt-8 p-2 bg-neutral rounded-md items-center']) }}>
    @if ($label)
    <x-inputs.label for="{{ $name }}">{{ $label }}</x-inputs.label>
    @endif

    <div class="w-28 h-28 p-2 my-2 bg-accent rounded-md flex items-center justify-center">
        <img id="{{ $name }}_preview" src="{{ $currentImage ? asset('storage/' . $currentImage) : '' }}" class="w-full h-full object-cover {{ $currentImage ? '' : 'hidden' }}" style="object-position: 0 0;" />
        <i id="{{ $name }}_icon" class="fas fa-file text-text text-4xl {{ $currentImage ? 'hidden' : '' }}"></i>
    </div>

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
            class="w-full text-text bg-secondary border rounded-md cursor-pointer @error($name) border-error @enderror"
            onclick="document.getElementById('{{ $name }}').click()">
    </div>

    @if ($messages && is_array($messages) && count($messages) > 0)
    <p class="text-error text-sm mt-1">{{ implode(', ', $messages) }}</p>
    @endif
</div>

<script>
    function previewImage(event, id) {
        const input = event.target;
        const preview = document.getElementById(id + '_preview');
        const icon = document.getElementById(id + '_icon');
        const textInput = document.getElementById(id + '_text');

        if (input.files && input.files[0]) {
            textInput.value = input.files[0].name;
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            icon.classList.remove('hidden');
            textInput.value = '';
        }
    }
</script>