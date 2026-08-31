<x-app-layout>
    <x-slot name="header">Keuangan — {{ $friend->name }}</x-slot>

    <div class="px-4 py-5">
        <div class="mb-3 text-xs text-[#8A8377] bg-[#F5F6FD] border border-[#E5E7F5] rounded-lg px-3 py-2">
            👁 Mode lihat saja (read-only) — data milik {{ $friend->name }}.
        </div>

        <form method="GET" action="{{ route('friends.keuangan', $friend) }}" class="mb-4">
            <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()"
                class="text-sm rounded-full border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] px-3 py-1.5">
        </form>

        <div class="bg-[#2563EB] rounded-2xl p-5 mb-4 text-white relative overflow-hidden">
            <p class="text-xs text-white/70 swk-heading">Saldo Bulan Ini</p>
            <p class="text-2xl font-semibold swk-heading mt-1">Rp{{ number_format($saldo, 0, ',', '.') }}</p>
            <div class="flex gap-6 mt-4">
                <div>
                    <p class="text-[10px] text-white/60">Masuk</p>
                    <p class="text-sm font-medium">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-white/60">Keluar</p>
                    <p class="text-sm font-medium">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-28 h-28 rounded-full bg-white/10"></div>
        </div>

        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-[#262135] swk-heading">Riwayat Pemasukan &amp; Pengeluaran</h3>
            <span class="text-xs text-[#9CA3AF]">{{ $transactions->total() }} transaksi</span>
        </div>

        <div class="space-y-2">
            @forelse ($transactions as $trx)
                <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex items-center gap-3">
                            <span class="w-9 h-9 rounded-full flex-shrink-0 flex items-center justify-center" style="background: {{ $trx->category->warna }}33;">
                                <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $trx->category->warna }}"></span>
                            </span>
                            <div>
                                <span class="text-sm text-[#262135] font-medium swk-heading">{{ $trx->category->nama }}</span>
                                <p class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $trx->tanggal->translatedFormat('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="text-sm font-semibold {{ $trx->category->type === 'pemasukan' ? 'text-[#2563EB]' : 'text-[#DC2626]' }}">
                            {{ $trx->category->type === 'pemasukan' ? '+' : '-' }}Rp{{ number_format($trx->jumlah, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada transaksi bulan ini.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</x-app-layout>
