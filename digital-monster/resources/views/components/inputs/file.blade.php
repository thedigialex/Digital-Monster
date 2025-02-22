@props(['label' => null, 'name' => 'file', 'id' => 'file', 'accept' => 'image/*'])

<div class="flex flex-col">
    @if ($label)
    <x-inputs.label for="{{ $id }}" class="pb-1">{{ $label }}</x-inputs.label>
    @endif

    <img id="{{ $id }}_preview" src="" class="hidden w-32 h-32 object-cover rounded-lg mb-2 border border-gray-300" />

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        accept="{{ $accept }}"
        onchange="previewImage(event, '{{ $id }}')"
        {!! $attributes->merge(['class' => 'border border-gray-300 rounded-md p-2 w-full focus:border-accent focus:ring-accent']) !!}>

    <script>
        function previewImage(event, id) {
            const input = event.target;
            const preview = document.getElementById(id + '_preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</div>