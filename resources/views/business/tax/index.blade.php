<x-business-layout :business="$business">
    <x-slot name="header">Perhitungan Pajak</x-slot>

    <div class="mb-4 text-xs text-[#B54708] bg-[#FFFAEB] border border-[#FEDF89] rounded-lg px-4 py-3">
        ⚠️ Ini kalkulator estimasi untuk bantu hitung, <strong>bukan integrasi resmi</strong> ke DJP/Coretax. Selalu cek ulang dengan konsultan pajak atau aplikasi resmi sebelum melapor.
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-[#465FFF] bg-[#ECF3FF] border border-[#C2D6FF] rounded-lg px-4 py-3">{{ session('status') }}</div>
    @endif

    <form method="GET" class="flex gap-2 mb-5">
        <div>
            <label class="text-xs text-[#667085]">Dari</label>
            <input type="date" name="dari" value="{{ $dari }}" class="block mt-1 rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
        </div>
        <div>
            <label class="text-xs text-[#667085]">Sampai</label>
            <input type="date" name="sampai" value="{{ $sampai }}" class="block mt-1 rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
        </div>
        <button type="submit" class="self-end text-xs font-medium text-white bg-[#465FFF] px-3 py-2 rounded-lg h-[38px]">Tampilkan</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white border border-[#E4E7EC] rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-[#101828]">Estimasi PPh</h3>
                <form method="POST" action="{{ route('business.tax.update-skema', $business) }}" id="skema-form" class="flex items-center gap-2">
                    @csrf @method('PUT')
                    <select name="skema_pajak" id="skema-select" onchange="document.getElementById('custom-fields').classList.toggle('hidden', this.value !== 'custom'); if(this.value !== 'custom') this.form.submit();" class="text-xs rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF]">
                        <option value="umkm_final" @selected($business->skema_pajak === 'umkm_final')>UMKM Final (0,5%)</option>
                        <option value="badan_normal" @selected($business->skema_pajak === 'badan_normal')>Badan Normal (22%, Pasal 31E)</option>
                        <option value="custom" @selected($business->skema_pajak === 'custom')>Persentase Sendiri</option>
                    </select>
                    <div id="custom-fields" class="flex items-center gap-1 {{ $business->skema_pajak !== 'custom' ? 'hidden' : '' }}">
                        <input type="number" name="pajak_custom_persen" step="0.01" min="0" max="100" value="{{ $business->pajak_custom_persen }}" placeholder="%" class="w-14 text-xs rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF]">
                        <select name="pajak_custom_basis" class="text-xs rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF]">
                            <option value="omzet" @selected($business->pajak_custom_basis === 'omzet')>x Omzet</option>
                            <option value="laba" @selected($business->pajak_custom_basis === 'laba')>x Laba</option>
                        </select>
                        <button type="submit" class="text-xs font-medium text-white bg-[#465FFF] px-2 py-1 rounded-lg">OK</button>
                    </div>
                </form>
            </div>
            <p class="text-xs text-[#667085]">{{ $pphLabel }}</p>
            @if ($pphDetail)
                <p class="text-[11px] text-[#98A2B3] mt-0.5">{{ $pphDetail }}</p>
            @endif
            <p class="text-2xl font-bold text-[#101828] mt-2">Rp{{ number_format($pphTerutang, 0, ',', '.') }}</p>
            <div class="mt-3 pt-3 border-t border-[#F2F4F7] text-xs text-[#667085] space-y-1">
                <div class="flex justify-between"><span>Omzet (Pendapatan)</span><span>Rp{{ number_format($omzet, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Laba Bersih</span><span>Rp{{ number_format($labaBersih, 0, ',', '.') }}</span></div>
            </div>
        </div>

        <div class="bg-white border border-[#E4E7EC] rounded-xl p-5">
            <h3 class="text-sm font-semibold text-[#101828] mb-3">Estimasi PPN</h3>
            <p class="text-xs text-[#667085]">{{ $ppnTerutang >= 0 ? 'PPN Kurang Bayar' : 'PPN Lebih Bayar (Kompensasi)' }}</p>
            <p class="text-2xl font-bold {{ $ppnTerutang >= 0 ? 'text-[#101828]' : 'text-[#079455]' }} mt-2">
                Rp{{ number_format(abs($ppnTerutang), 0, ',', '.') }}
            </p>
            <div class="mt-3 pt-3 border-t border-[#F2F4F7] text-xs text-[#667085] space-y-1">
                <div class="flex justify-between"><span>PPN Keluaran (dari penjualan)</span><span>Rp{{ number_format($totalPpnKeluaran, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>PPN Masukan (dari pembelian)</span><span>Rp{{ number_format($totalPpnMasukan, 0, ',', '.') }}</span></div>
            </div>
            <p class="text-[11px] text-[#98A2B3] mt-2">Dihitung dari saldo akun "PPN Keluaran" dan "PPN Masukan" di Chart of Account. Pastikan jurnal transaksi kena pajak sudah dicatat ke akun ini.</p>
        </div>
    </div>
</x-business-layout>
