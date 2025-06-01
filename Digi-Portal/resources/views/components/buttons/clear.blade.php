@props(['model', 'route', 'icon' => 'fa-edit', 'label' => 'Edit'])

<form action="{{ route('session.clear') }}" method="POST">
    @csrf
    <input type="hidden" name="model" value="{{ $model }}">
    <input type="hidden" name="route" value="{{ $route }}">    
    <x-buttons.button type="edit" icon="{{ $icon }}" label="{{ $label }}" />
</form>