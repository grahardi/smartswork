<x-app-layout>
    <x-slot name="header">Rekening</x-slot>

    <div class="px-4 py-5">
        <div class="flex items-center justify-end gap-3 mb-3">
            <a href="{{ route('rekening.pindah') }}" class="text-sm font-medium text-[#262135] px-4 py-2 rounded-full border border-[#E7E9F5]">Setor/Tarik Tunai</a>
            <a href="{{ route('rekening.create') }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-full">+ Tambah</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-[#DC2626] bg-[#FEE2E2] border border-[#FCA5A5] rounded-lg px-4 py-3">{{ session('error') }}</div>
        @endif

        {{-- Ringkasan total --}}
        @php $totalSemua = $cashFisik + $rekenings->sum(fn($r) => $r->saldoSekarang()); @endphp
        <div class="bg-[#262135] rounded-2xl p-5 mb-5 text-white">
            <p class="text-xs text-white/50">Total Saldo (Cash + Rekening)</p>
            <p class="text-2xl font-semibold swk-heading mt-1">Rp{{ number_format($totalSemua, 0, ',', '.') }}</p>
            <div class="flex items-center justify-between mt-3 pt-3 border-t border-white/10">
                <span class="text-xs text-white/70">💵 Cash Fisik</span>
                <span class="text-sm font-medium">Rp{{ number_format($cashFisik, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="space-y-2">
            @forelse ($rekenings as $rekening)
                <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-[#262135]">{{ $rekening->nama_bank }}</p>
                            @if ($rekening->no_rekening)
                                <p class="text-xs text-[#7B7F99]">{{ $rekening->no_rekening }}</p>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-[#2563EB]">Rp{{ number_format($rekening->saldoSekarang(), 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        <a href="{{ route('rekening.edit', $rekening) }}" class="text-xs text-[#2563EB]">Edit</a>
                        <form method="POST" action="{{ route('rekening.destroy', $rekening) }}" onsubmit="return confirm('Hapus rekening {{ $rekening->nama_bank }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-[#DC2626]">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada rekening. Transaksi tanpa rekening otomatis dihitung sebagai cash fisik.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
