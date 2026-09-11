<x-business-layout :business="$business">
    <x-slot name="header">Chart of Account</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('business.coa.create', $business) }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-full">+ Tambah Akun</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-[#DC2626] bg-[#FEE2E2] border border-[#FCA5A5] rounded-lg px-4 py-3">{{ session('error') }}</div>
        @endif

        @foreach (['aset' => 'ASET', 'kewajiban' => 'KEWAJIBAN', 'ekuitas' => 'EKUITAS', 'pendapatan' => 'PENDAPATAN', 'beban' => 'BEBAN'] as $tipe => $label)
            <div class="mb-4">
                <p class="text-xs font-medium text-[#8A8377] mb-2">{{ $label }}</p>
                <div class="space-y-1.5">
                    @forelse ($accounts[$tipe] ?? [] as $account)
                        <div class="bg-white border border-[#E7E9F5] rounded-lg px-4 py-2.5 flex items-center justify-between {{ !$account->is_active ? 'opacity-50' : '' }}">
                            <a href="{{ route('business.reports.buku-besar', [$business, $account]) }}" class="flex-1 min-w-0">
                                <span class="text-xs text-[#9CA3AF] mr-2">{{ $account->kode }}</span>
                                <span class="text-sm text-[#262135]">{{ $account->nama }}</span>
                            </a>
                            <a href="{{ route('business.coa.edit', [$business, $account]) }}" class="text-xs text-[#2563EB] flex-shrink-0">Edit</a>
                        </div>
                    @empty
                        <p class="text-xs text-[#9CA3AF]">Belum ada akun.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-business-layout>
