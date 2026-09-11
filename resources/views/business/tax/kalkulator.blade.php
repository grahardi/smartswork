<x-business-layout :business="$business">
    <x-slot name="header">Kalkulator Pajak</x-slot>

    <div class="mb-4 text-xs text-[#B54708] bg-[#FFFAEB] border border-[#FEDF89] rounded-lg px-4 py-3">
        ⚠️ Kalkulator manual — isi angka perkiraan sendiri (tidak diambil dari jurnal). Cocok untuk simulasi "kalau pemasukan segini, kira-kira pajak setahun berapa?". Bukan pelaporan resmi.
    </div>

    <div class="bg-white border border-[#E4E7EC] rounded-xl p-5 max-w-xl mb-5">
        <p class="text-xs text-[#667085] mb-4">
            Pilih skema pajak khusus untuk simulasi ini (independen dari setting utama di halaman <a href="{{ route('business.tax.index', $business) }}" class="text-[#465FFF] underline">Perhitungan Pajak</a>).
        </p>

        <form method="GET" class="space-y-4" id="kalkulator-form">
            <div>
                <x-input-label value="Periode Input" />
                <div class="mt-2 flex gap-4">
                    <label class="flex items-center gap-2 text-sm text-[#101828]">
                        <input type="radio" name="periode" value="bulanan" {{ (request('periode', 'bulanan') === 'bulanan') ? 'checked' : '' }}>
                        Per Bulan (dikali 12)
                    </label>
                    <label class="flex items-center gap-2 text-sm text-[#101828]">
                        <input type="radio" name="periode" value="tahunan" {{ request('periode') === 'tahunan' ? 'checked' : '' }}>
                        Per Tahun (langsung)
                    </label>
                </div>
            </div>

            <div>
                <x-input-label for="pemasukan" value="Pemasukan" />
                <x-text-input id="pemasukan" name="pemasukan" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ request('pemasukan') }}" placeholder="Contoh: 10000000" required />
            </div>

            <div>
                <x-input-label for="pengeluaran" value="Pengeluaran" />
                <x-text-input id="pengeluaran" name="pengeluaran" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ request('pengeluaran') }}" placeholder="Contoh: 4000000" required />
            </div>

            <div>
                <x-input-label value="Skema Pajak untuk Simulasi Ini" />
                <select name="skema_pajak" id="skema-kalkulator" onchange="document.getElementById('custom-kalkulator-fields').classList.toggle('hidden', this.value !== 'custom')" class="mt-1 block w-full rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                    <option value="umkm_final" @selected(request('skema_pajak', $business->skema_pajak) === 'umkm_final')>UMKM Final (0,5% x Omzet)</option>
                    <option value="badan_normal" @selected(request('skema_pajak', $business->skema_pajak) === 'badan_normal')>Badan Normal (22%, Pasal 31E)</option>
                    <option value="custom" @selected(request('skema_pajak', $business->skema_pajak) === 'custom')>Persentase Sendiri</option>
                </select>
                <div id="custom-kalkulator-fields" class="flex items-center gap-2 mt-2 {{ request('skema_pajak', $business->skema_pajak) !== 'custom' ? 'hidden' : '' }}">
                    <input type="number" name="pajak_custom_persen" step="0.01" min="0" max="100" value="{{ request('pajak_custom_persen', $business->pajak_custom_persen) }}" placeholder="Persen" class="w-24 rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                    <span class="text-sm text-[#667085]">%</span>
                    <select name="pajak_custom_basis" class="rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                        <option value="omzet" @selected(request('pajak_custom_basis', $business->pajak_custom_basis) === 'omzet')>dari Omzet</option>
                        <option value="laba" @selected(request('pajak_custom_basis', $business->pajak_custom_basis) === 'laba')>dari Laba</option>
                    </select>
                </div>
            </div>

            <x-primary-button>Hitung</x-primary-button>
        </form>
    </div>

    @if ($hasil)
        <div class="bg-white border border-[#E4E7EC] rounded-xl p-5 max-w-xl">
            <h3 class="text-sm font-semibold text-[#101828] mb-3">Hasil Proyeksi Setahun</h3>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#667085]">Omzet Setahun</span>
                    <span class="text-[#101828] font-medium">Rp{{ number_format($hasil['omzet_tahunan'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#667085]">Pengeluaran Setahun</span>
                    <span class="text-[#101828] font-medium">Rp{{ number_format($hasil['pengeluaran_tahunan'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-[#F2F4F7] pt-2">
                    <span class="text-[#667085]">Laba Setahun</span>
                    <span class="text-[#101828] font-medium">Rp{{ number_format($hasil['laba_tahunan'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4 bg-[#ECF3FF] rounded-lg p-4">
                <p class="text-xs text-[#465FFF]">{{ $hasil['label'] }}</p>
                @if ($hasil['detail'])
                    <p class="text-[11px] text-[#667085] mt-0.5">{{ $hasil['detail'] }}</p>
                @endif
                <p class="text-2xl font-bold text-[#101828] mt-1">Rp{{ number_format($hasil['pajak_setahun'], 0, ',', '.') }} <span class="text-sm font-normal text-[#667085]">/ tahun</span></p>
                <p class="text-xs text-[#667085] mt-1">≈ Rp{{ number_format($hasil['pajak_per_bulan'], 0, ',', '.') }} / bulan kalau mau disisihkan rutin</p>
            </div>
        </div>
    @endif
</x-business-layout>
