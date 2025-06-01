<div class="flex justify-end ">
    <form action="{{ $action }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this {{ $label }}?');">
        @csrf
        @method('DELETE')
        <x-buttons.button type="delete" label="Delete" icon="fa-trash" />
    </form>
</div>