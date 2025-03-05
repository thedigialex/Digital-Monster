@props([
'divClasses' => '',
'name' => '',
'onchange' => '',
'options' => [],
'value' => '',
'useOptionKey' => 'false',
'dataItems' => [],
'messages' => ''
])

<div id="{{ $name }}_div" class="{{ $divClasses }} mt-4">
    <x-inputs.label for="{{ $name }}" class="pb-1">{{ ucwords(str_replace('_', ' ', $name)) }}</x-inputs.label>

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($onchange) onchange="{{ $onchange }}" @endif
        class="w-full text-text bg-neutral focus:border-accent focus:ring-accent rounded-md  @error($name) border-error @enderror">
        <option value="" selected>- Select Option -</option>
        @foreach ($options as $optionKey => $optionValue)
        @if($useOptionKey == 'true')
        <option value="{{ $optionKey }}" {{ $value == $optionKey ? 'selected' : '' }} @if (isset($dataItems[$optionKey]))
            data-item='@json($dataItems[$optionKey])'
            @endif>
            {{ ucfirst($optionValue) }}
        </option>
        @else
        <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>
            {{ ucfirst($optionValue) }}
        </option>
        @endif
        @endforeach
    </select>

    @if ($messages && is_array($messages) && count($messages) > 0)
    <p class="text-error text-sm mt-1">{{ implode(', ', $messages) }}</p>
    @endif
</div>
