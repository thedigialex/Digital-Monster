@props(['model', 'model', 'route', 'icon' => 'fa-edit', 'label' => 'Edit'])

<form action="{{ route('session.clear') }}" method="POST">
    @csrf
    <input type="hidden" name="model" value="{{ $model }}">
    <input type="hidden" name="route" value="{{ $route }}">
    <x-primary-button icon="{{ $icon }}" label="{{ $label }}" type="submit"/>
</form>