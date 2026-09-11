<x-business-layout :business="$business">
    <x-slot name="header">Neraca</x-slot>

    <div class="px-4 py-5">
        <form method="GET" class="mb-4">
            <label class="text-xs text-[#7B7F99]">Per tanggal</label>
            <input type="date" name="sampai" value="{{ $sampaiTanggal }}" onchange="this.form.submit()"
                class="block mt-1 rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
        </form>

        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 mb-3">
            <p class="text-xs font-medium text-[#8A8377] mb-2">ASET</p>
            @foreach ($data['aset'] as $row)
                <div class="flex justify-between text-xs py-1">
                    <span class="text-[#262135]">{{ $row['account']->nama }}</span>
                    <span>{{ number_format($row['saldo'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="flex justify-between text-sm font-semibold border-t border-[#E5E7F5] mt-2 pt-2">
                <span>Total Aset</span>
                <span>{{ number_format($totalAset, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 mb-3">
            <p class="text-xs font-medium text-[#8A8377] mb-2">KEWAJIBAN</p>
            @foreach ($data['kewajiban'] as $row)
                <div class="flex justify-between text-xs py-1">
                    <span class="text-[#262135]">{{ $row['account']->nama }}</span>
                    <span>{{ number_format($row['saldo'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="flex justify-between text-sm font-semibold border-t border-[#E5E7F5] mt-2 pt-2">
                <span>Total Kewajiban</span>
                <span>{{ number_format($totalKewajiban, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 mb-3">
            <p class="text-xs font-medium text-[#8A8377] mb-2">EKUITAS</p>
            @foreach ($data['ekuitas'] as $row)
                <div class="flex justify-between text-xs py-1">
                    <span class="text-[#262135]">{{ $row['account']->nama }}</span>
                    <span>{{ number_format($row['saldo'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="flex justify-between text-xs py-1">
                <span class="text-[#262135]">Laba Berjalan (belum dipindah ke Modal)</span>
                <span>{{ number_format($labaTahunIni, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm font-semibold border-t border-[#E5E7F5] mt-2 pt-2">
                <span>Total Ekuitas</span>
                <span>{{ number_format($totalEkuitas, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-[#262135] text-white rounded-xl p-4 flex justify-between text-sm font-semibold">
            <span>Kewajiban + Ekuitas</span>
            <span>{{ number_format($totalKewajiban + $totalEkuitas, 0, ',', '.') }}</span>
        </div>

        @if (round($totalAset, 2) !== round($totalKewajiban + $totalEkuitas, 2))
            <p class="text-xs text-[#DC2626] mt-2">⚠️ Aset tidak sama dengan Kewajiban + Ekuitas — cek kembali jurnal yang sudah dicatat.</p>
        @endif
    </div>
</x-business-layout>
