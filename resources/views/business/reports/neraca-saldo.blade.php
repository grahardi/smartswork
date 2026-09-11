<x-business-layout :business="$business">
    <x-slot name="header">Neraca Saldo</x-slot>

    <div class="px-4 py-5">
        <form method="GET" class="mb-4">
            <label class="text-xs text-[#7B7F99]">Sampai tanggal</label>
            <input type="date" name="sampai" value="{{ $sampaiTanggal }}" onchange="this.form.submit()"
                class="block mt-1 rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
        </form>

        <div class="bg-white border border-[#E7E9F5] rounded-xl overflow-hidden">
            <table class="w-full text-xs">
                <thead class="bg-[#F5F6FD] text-[#7B7F99]">
                    <tr>
                        <th class="text-left px-3 py-2">Akun</th>
                        <th class="text-right px-3 py-2">Debit</th>
                        <th class="text-right px-3 py-2">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $row)
                        <tr class="border-t border-[#F0EBDF]">
                            <td class="px-3 py-2 text-[#262135]">{{ $row['account']->kode }} {{ $row['account']->nama }}</td>
                            <td class="px-3 py-2 text-right">{{ $row['debit'] > 0 ? number_format($row['debit'], 0, ',', '.') : '-' }}</td>
                            <td class="px-3 py-2 text-right">{{ $row['kredit'] > 0 ? number_format($row['kredit'], 0, ',', '.') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-[#F5F6FD] font-semibold">
                    <tr class="border-t border-[#E5E7F5]">
                        <td class="px-3 py-2">Total</td>
                        <td class="px-3 py-2 text-right">{{ number_format($totalDebit, 0, ',', '.') }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($totalKredit, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if (round($totalDebit, 2) !== round($totalKredit, 2))
            <p class="text-xs text-[#DC2626] mt-2">⚠️ Total debit dan kredit tidak seimbang — ada kemungkinan data jurnal bermasalah.</p>
        @endif
    </div>
</x-business-layout>
