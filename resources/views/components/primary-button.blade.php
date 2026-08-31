<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-[#2563EB] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-[#1D4ED8] focus:outline-none focus:ring-2 focus:ring-[#2563EB] focus:ring-offset-2 transition disabled:opacity-50']) }}>
    {{ $slot }}
</button>
