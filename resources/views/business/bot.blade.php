<x-business-layout :business="$business">
    <x-slot name="header">Bot AI</x-slot>

    <div class="bg-[#ECF3FF] border border-[#C2D6FF] rounded-xl p-5 mb-5">
        <div class="flex items-center gap-2 mb-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#465FFF" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
            <h3 class="text-sm font-semibold text-[#101828]">Catat transaksi pakai kalimat biasa</h3>
        </div>
        <p class="text-xs text-[#475467]">Contoh: "beli meja 400rb", "bayar listrik 350ribu", "terima pembayaran client 5jt". AI akan usulkan baris jurnalnya, kamu tinggal cek dan simpan.</p>
    </div>

    <div class="flex gap-2 mb-5">
        <input type="text" id="ai-input" placeholder="Tulis transaksinya di sini..." class="flex-1 rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
        <button type="button" onclick="swkAiParse()" id="ai-btn" class="text-sm font-medium text-white bg-[#465FFF] px-5 py-2 rounded-lg whitespace-nowrap">Proses</button>
    </div>
    <p id="ai-status" class="text-xs text-[#D92D20] mb-4"></p>

    <form method="POST" action="{{ route('business.journal.store', $business) }}" class="space-y-5" id="journal-form">
        @csrf

        <div class="grid grid-cols-2 gap-3">
            <div>
                <x-input-label for="tanggal" value="Tanggal" />
                <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" value="{{ now()->toDateString() }}" required />
            </div>
            <div>
                <x-input-label for="nomor_referensi" value="No. Referensi" />
                <x-text-input id="nomor_referensi" name="nomor_referensi" type="text" class="mt-1 block w-full" placeholder="Opsional" />
            </div>
        </div>

        <div>
            <x-input-label for="keterangan" value="Keterangan" />
            <textarea id="keterangan" name="keterangan" rows="2" class="mt-1 block w-full rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm"></textarea>
        </div>

        <div id="empty-hint" class="text-sm text-[#98A2B3] text-center py-6 border border-dashed border-[#E4E7EC] rounded-lg">
            Belum ada baris jurnal. Tulis transaksi di atas dan klik "Proses", atau
            <a href="{{ route('business.journal.create', $business) }}" class="text-[#465FFF] underline">isi manual di sini</a>.
        </div>

        <div id="lines-wrap" class="space-y-2"></div>

        <div id="totals-wrap" class="hidden flex items-center justify-between text-sm font-medium bg-[#F9FAFB] rounded-lg px-3 py-2">
            <span>Total Debit: <span id="total-debit">Rp0</span></span>
            <span>Total Kredit: <span id="total-kredit">Rp0</span></span>
        </div>

        <div id="submit-wrap" class="hidden flex items-center gap-4 pt-2">
            <x-primary-button>Simpan Jurnal</x-primary-button>
            <button type="button" onclick="document.getElementById('lines-wrap').innerHTML=''; document.getElementById('empty-hint').classList.remove('hidden'); document.getElementById('totals-wrap').classList.add('hidden'); document.getElementById('submit-wrap').classList.add('hidden');" class="text-sm text-[#667085]">Bersihkan</button>
        </div>
    </form>

    <script>
        const accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'label' => $a->kode . ' - ' . $a->nama]));
        let lineIndex = 0;

        function swkAddLine(prefill) {
            document.getElementById('empty-hint').classList.add('hidden');
            document.getElementById('totals-wrap').classList.remove('hidden');
            document.getElementById('submit-wrap').classList.remove('hidden');

            const wrap = document.getElementById('lines-wrap');
            const i = lineIndex++;
            const div = document.createElement('div');
            div.className = 'bg-white border border-[#E4E7EC] rounded-lg p-3 space-y-2';
            div.innerHTML = `
                <select name="lines[${i}][chart_of_account_id]" required class="block w-full rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                    <option value="">Pilih akun</option>
                    ${accounts.map(a => `<option value="${a.id}" ${prefill && prefill.chart_of_account_id == a.id ? 'selected' : ''}>${a.label}</option>`).join('')}
                </select>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" step="0.01" min="0" name="lines[${i}][debit]" placeholder="Debit" onchange="swkRecalc()" value="${prefill && prefill.debit > 0 ? prefill.debit : ''}" class="rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                    <input type="number" step="0.01" min="0" name="lines[${i}][kredit]" placeholder="Kredit" onchange="swkRecalc()" value="${prefill && prefill.kredit > 0 ? prefill.kredit : ''}" class="rounded-lg border-[#D0D5DD] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                </div>
                <button type="button" onclick="this.closest('div.bg-white').remove(); swkRecalc();" class="text-[11px] text-[#D92D20]">Hapus baris</button>
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

        async function swkAiParse() {
            const teks = document.getElementById('ai-input').value.trim();
            const status = document.getElementById('ai-status');
            const btn = document.getElementById('ai-btn');
            if (!teks) return;

            status.textContent = '';
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            try {
                const res = await fetch('{{ route("business.journal.ai-parse", $business) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ teks, tanggal_hari_ini: document.getElementById('tanggal').value }),
                });
                const data = await res.json();

                if (!res.ok) {
                    status.classList.remove('text-[#079455]');
                    status.classList.add('text-[#D92D20]');
                    status.textContent = data.error || 'Gagal memproses.';
                    return;
                }

                document.getElementById('lines-wrap').innerHTML = '';
                document.getElementById('tanggal').value = data.tanggal;
                document.getElementById('keterangan').value = data.keterangan;
                data.lines.forEach(line => swkAddLine(line));
                swkRecalc();
                status.classList.remove('text-[#D92D20]');
                status.classList.add('text-[#079455]');
                status.textContent = 'Berhasil diusulkan AI - cek lagi sebelum simpan ya.';
                document.getElementById('ai-input').value = '';
            } catch (e) {
                status.textContent = 'Gagal menghubungi server.';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Proses';
            }
        }
    </script>
</x-business-layout>
