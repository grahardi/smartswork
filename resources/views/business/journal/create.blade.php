<x-business-layout :business="$business">
    <x-slot name="header">Catat Jurnal</x-slot>

    <div class="px-4 py-5">
        {{-- AI Assistant --}}
        <div class="bg-[#ECF3FF] border border-[#C2D6FF] rounded-xl p-4 mb-5">
            <div class="flex items-center gap-2 mb-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#465FFF" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                <h3 class="text-sm font-semibold text-[#101828]">Catat dengan AI</h3>
            </div>
            <p class="text-xs text-[#475467] mb-2">Tulis transaksinya pakai kalimat biasa, AI akan usulkan baris jurnalnya (tetap perlu kamu cek sebelum simpan).</p>
            <div class="flex gap-2">
                <input type="text" id="ai-input" placeholder="Contoh: beli meja 400rb" class="flex-1 rounded-lg border-[#C2D6FF] focus:border-[#465FFF] focus:ring-[#465FFF] text-sm">
                <button type="button" onclick="swkAiParse()" id="ai-btn" class="text-sm font-medium text-white bg-[#465FFF] px-4 py-2 rounded-lg whitespace-nowrap">Proses</button>
            </div>
            <p id="ai-status" class="text-xs text-[#D92D20] mt-2"></p>
        </div>

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

            <div class="flex items-center justify-between text-sm font-medium bg-[#F9FAFB] rounded-lg px-3 py-2">
                <span>Total Debit: <span id="total-debit">Rp0</span></span>
                <span>Total Kredit: <span id="total-kredit">Rp0</span></span>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan Jurnal</x-primary-button>
                <a href="{{ route('business.journal.index', $business) }}" class="text-sm text-[#667085]">Batal</a>
            </div>
        </form>
    </div>

    <script>
        const accounts = @json($accounts->map(fn($a) => ['id' => $a->id, 'label' => $a->kode . ' - ' . $a->nama]));
        let lineIndex = 0;

        function swkAddLine(prefill = null) {
            const wrap = document.getElementById('lines-wrap');
            const i = lineIndex++;
            const div = document.createElement('div');
            div.className = 'bg-white border border-[#E4E7EC] rounded-lg p-3 space-y-2';
            div.innerHTML = `
                <select name="lines[${i}][chart_of_account_id]" required class="block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                    <option value="">Pilih akun</option>
                    ${accounts.map(a => `<option value="${a.id}" ${prefill && prefill.chart_of_account_id == a.id ? 'selected' : ''}>${a.label}</option>`).join('')}
                </select>
                <div class="grid grid-cols-2 gap-2">
                    <input type="number" step="0.01" min="0" name="lines[${i}][debit]" placeholder="Debit" onchange="swkRecalc()" value="${prefill && prefill.debit > 0 ? prefill.debit : ''}" class="rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                    <input type="number" step="0.01" min="0" name="lines[${i}][kredit]" placeholder="Kredit" onchange="swkRecalc()" value="${prefill && prefill.kredit > 0 ? prefill.kredit : ''}" class="rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                </div>
                <input type="text" name="lines[${i}][keterangan]" placeholder="Keterangan baris (opsional)" class="block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                <button type="button" onclick="this.closest('div.bg-white').remove(); swkRecalc();" class="text-[11px] text-[#D92D20]">Hapus baris</button>
            `;
            wrap.appendChild(div);
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
                    status.textContent = data.error || 'Gagal memproses.';
                    return;
                }

                // Bersihkan baris kosong lama, isi dengan usulan AI.
                document.getElementById('lines-wrap').innerHTML = '';
                document.getElementById('tanggal').value = data.tanggal;
                document.getElementById('keterangan').value = data.keterangan;
                data.lines.forEach(line => swkAddLine(line));
                swkRecalc();
                status.classList.remove('text-[#D92D20]');
                status.classList.add('text-[#079455]');
                status.textContent = 'Berhasil diusulkan AI - cek lagi sebelum simpan ya.';
            } catch (e) {
                status.textContent = 'Gagal menghubungi server.';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Proses';
            }
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
