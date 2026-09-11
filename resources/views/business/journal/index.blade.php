<x-business-layout :business="$business">
    <x-slot name="header">Jurnal Umum</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('business.journal.create', $business) }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-full">+ Catat Jurnal</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($entries as $entry)
                <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-[#262135]">{{ $entry->tanggal->translatedFormat('d M Y') }}</span>
                        <span class="text-xs text-[#9CA3AF]">{{ $entry->nomor_referensi }}</span>
                    </div>
                    @if ($entry->keterangan)
                        <p class="text-xs text-[#7B7F99] mt-1">{{ $entry->keterangan }}</p>
                    @endif

                    <div class="mt-2 space-y-1">
                        @foreach ($entry->lines as $line)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-[#262135]">{{ $line->account->kode }} - {{ $line->account->nama }}</span>
                                <span class="text-[#7B7F99]">
                                    @if ($line->debit > 0) D: Rp{{ number_format($line->debit, 0, ',', '.') }} @endif
                                    @if ($line->kredit > 0) K: Rp{{ number_format($line->kredit, 0, ',', '.') }} @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('business.journal.destroy', [$business, $entry]) }}" onsubmit="return confirm('Hapus jurnal ini?')" class="mt-2">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-[11px] text-[#DC2626]">Hapus</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada jurnal tercatat.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $entries->links() }}</div>
    </div>
</x-business-layout>
