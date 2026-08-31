@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-[#E5E7F5] text-[#1F2333] text-sm focus:border-[#4F46E5] focus:ring-[#4F46E5]']) }}>
