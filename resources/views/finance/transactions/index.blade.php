<x-app-layout>
    <x-slot name="header">Keuangan</x-slot>

    <div class="px-4 py-5">
        <div class="flex items-center justify-between mb-3">
            <form method="GET" action="{{ route('finance.transactions.index') }}">
                <input type="month" name="bulan" value="{{ $bulan }}" onchange="this.form.submit()"
                    class="text-sm rounded-lg border-[#DAD4C4] focus:border-[#3E5C4E] focus:ring-[#3E5C4E]">
            </form>
            <div class="flex items-center gap-3">
                <a href="{{ route('finance.categories.index') }}" class="text-xs text-[#3F5C7A]">Kategori</a>
                <a href="{{ route('finance.transactions.create') }}" class="text-sm font-medium text-white bg-[#3E5C4E] px-4 py-2 rounded-lg">+ Catat</a>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#3E5C4E] bg-[#EDF3EF] border border-[#CFE0D6] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-3 gap-2 mb-4">
            <div class="bg-white border border-[#EAE4D6] rounded-xl p-3">
                <p class="text-[10px] text-[#8A8377]">Masuk</p>
                <p class="text-sm font-semibold text-[#3E5C4E] mt-1">Rp{{ number_format($totalMasuk, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-[#EAE4D6] rounded-xl p-3">
                <p class="text-[10px] text-[#8A8377]">Keluar</p>
                <p class="text-sm font-semibold text-[#A32D2D] mt-1">Rp{{ number_format($totalKeluar, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white border border-[#EAE4D6] rounded-xl p-3">
                <p class="text-[10px] text-[#8A8377]">Saldo</p>
                <p class="text-sm font-semibold text-[#2A2621] mt-1">Rp{{ number_format($saldo, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="space-y-2">
            @forelse ($transactions as $trx)
                <div class="bg-white border border-[#EAE4D6] rounded-xl p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background: {{ $trx->category->warna }}"></span>
                                <span class="text-sm text-[#2A2621]">{{ $trx->category->nama }}</span>
                            </div>
                            @if ($trx->keterangan)
                                <p class="text-xs text-[#8A8377] mt-1">{{ $trx->keterangan }}</p>
                            @endif
                            <p class="text-[11px] text-[#8A8377] mt-1">{{ $trx->tanggal->translatedFormat('d M Y') }}@if($trx->workplace) · {{ $trx->workplace->nama }} @endif</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-semibold {{ $trx->category->type === 'pemasukan' ? 'text-[#3E5C4E]' : 'text-[#A32D2D]' }}">
                                {{ $trx->category->type === 'pemasukan' ? '+' : '-' }}Rp{{ number_format($trx->jumlah, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center gap-2 justify-end mt-1">
                                <a href="{{ route('finance.transactions.edit', $trx) }}" class="text-[11px] text-[#3F5C7A]">Edit</a>
                                <form method="POST" action="{{ route('finance.transactions.destroy', $trx) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-[11px] text-[#A32D2D]">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#6E675A] text-center py-8">Belum ada transaksi bulan ini.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
</x-app-layout>
