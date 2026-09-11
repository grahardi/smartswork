<x-business-layout :business="$business">
    <x-slot name="header">Catat Jurnal</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('business.journal.store', $business) }}" class="space-y-5" id="journal-form">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label for="tanggal" value="Tanggal" />
                    <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" value="{{ old('tanggal', now()->toDateString()) }}" required />
                </div>
                <div>
                    <x-input-label for="nomor_referensi" value="No. Referensi" />
                    <x-text-input id="nomor_referensi" name="nomor_referensi" type="text" class="mt-1 block w-full" value="{{ old('nomor_referensi') }}" placeholder="Opsional" />
                </div>
            </div>

            <div>
                <x-input-label for="keterangan" value="Keterangan" />
                <textarea id="keterangan" name="keterangan" rows="2" class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('keterangan') }}</textarea>
            </div>

            <x-input-error :messages="$errors->get('lines')" class="text-sm" />

            <div>
                <x-input-label value="Baris Jurnal" />
                <div id="lines-wrap" class="space-y-2 mt-2"></div>
                <button type="button" onclick="swkAddLine()" class="mt-2 text-xs text-[#2563EB] font-medium">+ Tambah Baris</button>
            </div>

            <div class="flex items-center justify-between text-sm font-medium bg-[#F5F6FD] rounded-lg px-3 py-2">
                <span>Total Debit: <span id="total-debit">Rp0</span></span>
                <span>Total Kredit: <span id="total-kredit">Rp0</span></span>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan Jurnal</x-primary-button>
                <a href="{{ route('business.journal.index', $business) }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>

    <script>
        const accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'label' => $a->kode . ' - ' . $a->nama]));
        let lineIndex = 0;

        function swkAddLine() {
            const wrap = document.getElementById('lines-wrap');
            const i = lineIndex++;
            const div = document.createElement('div');
            div.className = 'bg-white border border-[#E7E9F5] rounded-lg p-3 space-y-2';
            div.innerHTML = `
                <select name="lines[${i}][chart_of_account_id]" required class="block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                    <option value="">Pilih akun</option>
                    ${accounts.map(a => `<option value="${a.id}">${a.label}</option>`).join('')}
                </select>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" step="0.01" min="0" name="lines[${i}][debit]" placeholder="Debit" onchange="swkRecalc()" class="rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                    <input type="number" step="0.01" min="0" name="lines[${i}][kredit]" placeholder="Kredit" onchange="swkRecalc()" class="rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                </div>
                <input type="text" name="lines[${i}][keterangan]" placeholder="Keterangan baris (opsional)" class="block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                <button type="button" onclick="this.closest('div.bg-white').remove(); swkRecalc();" class="text-[11px] text-[#DC2626]">Hapus baris</button>
            `;
            wrap.appendChild(div);
        }

        function swkRecalc() {
            let totalDebit = 0, totalKredit = 0;
            document.querySelectorAll('input[name^="lines"][name$="[debit]"]').forEach(el => totalDebit += parseFloat(el.value) || 0);
            document.querySelectorAll('input[name^="lines"][name$="[kredit]"]').forEach(el => totalKredit += parseFloat(el.value) || 0);
            document.getElementById('total-debit').textContent = 'Rp' + totalDebit.toLocaleString('id-ID');
            document.getElementById('total-kredit').textContent = 'Rp' + totalKredit.toLocaleString('id-ID');
        }

        // Mulai dengan 2 baris kosong (minimal untuk double-entry).
        swkAddLine();
        swkAddLine();
    </script>
</x-business-layout>
