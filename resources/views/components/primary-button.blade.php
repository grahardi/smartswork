<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-2.5 bg-[#4F46E5] border border-transparent rounded-lg font-medium text-sm text-white hover:bg-[#3730A3] focus:outline-none focus:ring-2 focus:ring-[#4F46E5] focus:ring-offset-2 transition disabled:opacity-50']) }}>
    {{ $slot }}
</button>
