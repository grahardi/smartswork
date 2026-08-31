@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-xs font-medium text-[#1F2333]']) }}>
    {{ $value ?? $slot }}
</label>
