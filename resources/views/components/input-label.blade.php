@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-medium text-[#2A2621]']) }}>
    {{ $value ?? $slot }}
</label>
