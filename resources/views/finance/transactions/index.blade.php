<x-app-layout>
    <x-slot name="header">Keuangan</x-slot>

    <div class="px-4 py-5">
        <div class="flex items-center justify-between mb-4">
            <form method="GET" action="{{ route('finance.transactions.index') }}">
                <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()"
                    class="text-sm rounded-full border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] px-3 py-1.5">
            </form>
            <div class="flex items-center gap-3">
                <a href="{{ route('finance.categories.index') }}" class="text-xs text-[#262135] font-medium">Kategori</a>
                <a href="{{ route('finance.transactions.create') }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-full">+ Catat</a>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        {{-- Kartu ringkasan biru ala Statistics Figma --}}
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
                                @if ($trx->keterangan)
                                    <p class="text-xs text-[#7B7F99] mt-0.5">{{ $trx->keterangan }}</p>
                                @endif
                                <p class="text-[11px] text-[#9CA3AF] mt-0.5">{{ $trx->tanggal->translatedFormat('d M Y') }}@if($trx->workplace) · {{ $trx->workplace->nama }} @endif</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-semibold {{ $trx->category->type === 'pemasukan' ? 'text-[#2563EB]' : 'text-[#DC2626]' }}">
                                {{ $trx->category->type === 'pemasukan' ? '+' : '-' }}Rp{{ number_format($trx->jumlah, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center gap-2 justify-end mt-1">
                                <a href="{{ route('finance.transactions.edit', $trx) }}" class="text-[11px] text-[#2563EB]">Edit</a>
                                <form method="POST" action="{{ route('finance.transactions.destroy', $trx) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] text-[#DC2626]">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada transaksi bulan ini.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</x-app-layout>
