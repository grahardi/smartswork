<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-[#3E5C4E] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-[#2F473B] focus:outline-none focus:ring-2 focus:ring-[#3E5C4E] focus:ring-offset-2 transition disabled:opacity-50']) }}>
    {{ $slot }}
</button>
