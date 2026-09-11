<x-business-layout :business="$business">
    <x-slot name="header">Buku Besar</x-slot>

    <div class="px-4 py-5">
        <p class="text-sm font-semibold text-[#101828] swk-heading mb-1">{{ $account->kode }} — {{ $account->nama }}</p>
        <p class="text-xs text-[#667085] mb-4">Saldo normal: {{ ucfirst($account->saldo_normal) }}</p>

        <div class="bg-white border border-[#E4E7EC] rounded-xl overflow-hidden">
            <table class="w-full text-xs">
                <thead class="bg-[#F9FAFB] text-[#667085]">
                    <tr>
                        <th class="text-left px-3 py-2">Tanggal</th>
                        <th class="text-left px-3 py-2">Keterangan</th>
                        <th class="text-right px-3 py-2">Debit</th>
                        <th class="text-right px-3 py-2">Kredit</th>
                        <th class="text-right px-3 py-2">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr class="border-t border-[#E4E7EC]">
                            <td class="px-3 py-2 text-[#101828]">{{ $row['tanggal']->translatedFormat('d/m/Y') }}</td>
                            <td class="px-3 py-2 text-[#667085]">{{ $row['keterangan'] }}</td>
                            <td class="px-3 py-2 text-right">{{ $row['debit'] > 0 ? number_format($row['debit'], 0, ',', '.') : '-' }}</td>
                            <td class="px-3 py-2 text-right">{{ $row['kredit'] > 0 ? number_format($row['kredit'], 0, ',', '.') : '-' }}</td>
                            <td class="px-3 py-2 text-right font-medium">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-3 py-6 text-center text-[#98A2B3]">Belum ada transaksi di akun ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-business-layout>
