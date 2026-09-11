<x-business-layout :business="$business">
    <x-slot name="header">Laba Rugi</x-slot>

    <div class="px-4 py-5">
        <form method="GET" class="flex gap-2 mb-4">
            <div>
                <label class="text-xs text-[#667085]">Dari</label>
                <input type="date" name="dari" value="{{ $dari }}" class="block mt-1 rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
            </div>
            <div>
                <label class="text-xs text-[#667085]">Sampai</label>
                <input type="date" name="sampai" value="{{ $sampai }}" class="block mt-1 rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
            </div>
            <button type="submit" class="self-end text-xs font-medium text-white bg-[#2563EB] px-3 py-2 rounded-lg h-[38px]">Tampilkan</button>
        </form>

        <div class="bg-white border border-[#E4E7EC] rounded-xl p-4 mb-3">
            <p class="text-xs font-medium text-[#8A8377] mb-2">PENDAPATAN</p>
            @foreach ($pendapatan as $row)
                <div class="flex justify-between text-xs py-1">
                    <span class="text-[#101828]">{{ $row['account']->nama }}</span>
                    <span>{{ number_format($row['jumlah'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="flex justify-between text-sm font-semibold border-t border-[#E5E7F5] mt-2 pt-2">
                <span>Total Pendapatan</span>
                <span>{{ number_format($total_pendapatan, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white border border-[#E4E7EC] rounded-xl p-4 mb-3">
            <p class="text-xs font-medium text-[#8A8377] mb-2">BEBAN</p>
            @foreach ($beban as $row)
                <div class="flex justify-between text-xs py-1">
                    <span class="text-[#101828]">{{ $row['account']->nama }}</span>
                    <span>{{ number_format($row['jumlah'], 0, ',', '.') }}</span>
                </div>
            @endforeach
            <div class="flex justify-between text-sm font-semibold border-t border-[#E5E7F5] mt-2 pt-2">
                <span>Total Beban</span>
                <span>{{ number_format($total_beban, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="rounded-xl p-4 flex justify-between text-sm font-semibold {{ $laba_bersih >= 0 ? 'bg-[#EFF6FF] text-[#2563EB]' : 'bg-[#FEF3F2] text-[#D92D20]' }}">
            <span>{{ $laba_bersih >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</span>
            <span>Rp{{ number_format(abs($laba_bersih), 0, ',', '.') }}</span>
        </div>

        <p class="text-[11px] text-[#98A2B3] mt-3">
            💡 Angka di laporan ini bisa jadi acuan saat mengisi laporan pajak/Coretax — tapi ini bukan integrasi resmi, pastikan cek ulang dengan konsultan/aplikasi pajak resmi sebelum submit.
        </p>
    </div>
</x-business-layout>
