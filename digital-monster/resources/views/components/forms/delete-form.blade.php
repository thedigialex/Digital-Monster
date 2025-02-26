<div class="flex justify-end w-full">
    <form action="{{ $action }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this {{ $label }}?');">
        @csrf
        @method('DELETE')
        <x-danger-button />
    </form>
</div>