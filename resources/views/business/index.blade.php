<x-business-layout>
    <x-slot name="header">SMARTS Business</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('business.create') }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-full">+ Daftarkan Business</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($businesses as $business)
                <a href="{{ route('business.dashboard', $business) }}" class="block bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <p class="text-sm font-semibold text-[#262135] swk-heading">{{ $business->nama_usaha }}</p>
                    <p class="text-xs text-[#7B7F99] mt-1">{{ $business->jenis_usaha ?? '—' }} · {{ $business->pivot->role }}</p>
                </a>
            @empty
                <div class="text-center py-10">
                    <p class="text-sm text-[#7B7F99] mb-2">Belum ada business. Daftarkan yang pertama untuk mulai kelola akuntansi (Neraca, Jurnal, Laba Rugi) yang bisa jadi acuan laporan pajak.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-business-layout>
