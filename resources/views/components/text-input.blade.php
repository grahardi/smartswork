@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-lg border-[#DAD4C4] text-[#2A2621] text-sm focus:border-[#3E5C4E] focus:ring-[#3E5C4E]']) }}>
