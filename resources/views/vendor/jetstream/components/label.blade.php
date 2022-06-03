@props(['value', 'required'])

@php
    $isRequired = $required ?? false;
@endphp

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if ($isRequired)
        <span class="text-red-500">*</span>
    @endif
</label>
