@props(['model', 'id', 'route', 'icon' => 'fa-edit', 'label' => 'Edit'])

<form action="{{ route('session.store') }}" method="POST">
    @csrf
    <input type="hidden" name="model" value="{{ $model }}">
    <input type="hidden" name="id" value="{{ $id }}">
    <input type="hidden" name="route" value="{{ $route }}">
    <x-primary-button icon="{{ $icon }}" label="{{ $label }}" type="submit"/>
</form>