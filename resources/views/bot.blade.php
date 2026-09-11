<x-app-layout>
    <x-slot name="header">Bot AI</x-slot>

    <div class="px-4 py-5">
        <div class="bg-[#EFF6FF] border border-[#BFDBFE] rounded-xl p-4 mb-5">
            <div class="flex items-center gap-2 mb-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                <h3 class="text-sm font-semibold text-[#1F2333]">Catat apa saja, cukup ngomong/ketik</h3>
            </div>
            <p class="text-xs text-[#7B7F99]">Bot ini otomatis tahu apakah ini soal <strong>uang</strong> (masuk Keuangan) atau soal <strong>kegiatan</strong> (masuk Aksi Harian). Contoh: "bayar bensin 50rb" atau "ke toko Amanah beli roti sama susu jam 3 sore".</p>
        </div>

        <div class="flex gap-2 mb-2">
            <input type="text" id="ai-input" placeholder="Ketik di sini..." class="flex-1 rounded-lg border-[#BFDBFE] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
            <button type="button" onclick="swkAiParseText()" id="ai-btn" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-lg">Proses</button>
            <button type="button" onclick="swkToggleRecord()" id="mic-btn" class="w-10 h-10 flex-shrink-0 rounded-full bg-white border border-[#BFDBFE] flex items-center justify-center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v4"/></svg>
            </button>
        </div>
        <p id="ai-status" class="text-xs text-[#DC2626] mb-5"></p>

        {{-- Draft Keuangan --}}
        <form method="POST" action="{{ route('finance.transactions.store') }}" id="form-keuangan" class="hidden bg-white border border-[#EAE4D6] rounded-xl p-4 space-y-4">
            @csrf
            <p class="text-xs font-medium text-[#2563EB]">💰 Terdeteksi: Transaksi Keuangan</p>
            <input type="hidden" name="finance_category_id" id="k_finance_category_id">
            <p class="text-sm text-[#1F2333]" id="k_kategori_label"></p>
            <div>
                <x-input-label value="Jumlah (Rp)" />
                <x-text-input name="jumlah" id="k_jumlah" type="number" step="0.01" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label value="Tanggal" />
                <x-text-input name="tanggal" id="k_tanggal" type="date" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label value="Keterangan" />
                <textarea name="keterangan" id="k_keterangan" rows="2" class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm"></textarea>
            </div>
            <x-primary-button>Simpan ke Keuangan</x-primary-button>
        </form>

        {{-- Draft Aksi Harian --}}
        <form method="POST" action="{{ route('daily-actions.store') }}" id="form-aksi" class="hidden bg-white border border-[#EAE4D6] rounded-xl p-4 space-y-4">
            @csrf
            <p class="text-xs font-medium text-[#B9832F]">📝 Terdeteksi: Aksi Harian</p>
            <input type="hidden" name="project_id" id="a_project_id">
            <p class="text-sm text-[#1F2333]" id="a_project_label"></p>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <x-input-label value="Tanggal" />
                    <x-text-input name="tanggal" id="a_tanggal" type="date" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label value="Waktu" />
                    <x-text-input name="waktu" id="a_waktu" type="time" class="mt-1 block w-full" />
                </div>
            </div>
            <div>
                <x-input-label value="Keterangan" />
                <textarea name="keterangan" id="a_keterangan" rows="2" class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm"></textarea>
            </div>
            <x-primary-button>Simpan ke Aksi Harian</x-primary-button>
        </form>
    </div>

    <script>
        async function swkAiParseText() {
            const teks = document.getElementById('ai-input').value.trim();
            if (!teks) return;
            await swkAiSend({ teks });
        }

        let mediaRecorder = null, audioChunks = [], isRecording = false;

        async function swkToggleRecord() {
            const micBtn = document.getElementById('mic-btn');
            const status = document.getElementById('ai-status');

            if (!isRecording) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];
                    mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                    mediaRecorder.onstop = async () => {
                        const blob = new Blob(audioChunks, { type: 'audio/webm' });
                        stream.getTracks().forEach(t => t.stop());
                        await swkAiSend({ audio: blob });
                    };
                    mediaRecorder.start();
                    isRecording = true;
                    micBtn.classList.add('bg-[#DC2626]');
                    micBtn.querySelector('svg').setAttribute('stroke', '#fff');
                    status.textContent = 'Merekam... klik lagi untuk berhenti.';
                } catch (e) {
                    status.textContent = 'Tidak bisa akses mic (perlu izin browser / HTTPS).';
                }
            } else {
                mediaRecorder.stop();
                isRecording = false;
                micBtn.classList.remove('bg-[#DC2626]');
                micBtn.querySelector('svg').setAttribute('stroke', '#2563EB');
            }
        }

        async function swkAiSend({ teks = null, audio = null }) {
            const status = document.getElementById('ai-status');
            const btn = document.getElementById('ai-btn');
            status.classList.remove('text-[#16A34A]');
            status.classList.add('text-[#DC2626]');
            status.textContent = audio ? 'Memproses rekaman...' : '';
            btn.disabled = true;

            document.getElementById('form-keuangan').classList.add('hidden');
            document.getElementById('form-aksi').classList.add('hidden');

            const formData = new FormData();
            if (teks) formData.append('teks', teks);
            if (audio) formData.append('audio', audio, 'rekaman.webm');
            formData.append('tanggal_hari_ini', new Date().toISOString().slice(0, 10));

            try {
                const res = await fetch('{{ route("bot.parse") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData,
                });
                const data = await res.json();

                if (!res.ok) {
                    status.textContent = data.error || 'Gagal memproses.';
                    return;
                }

                if (data.jenis === 'keuangan') {
                    document.getElementById('k_finance_category_id').value = data.finance_category_id;
                    document.getElementById('k_kategori_label').textContent = data.kategori_label;
                    document.getElementById('k_jumlah').value = data.jumlah;
                    document.getElementById('k_tanggal').value = data.tanggal;
                    document.getElementById('k_keterangan').value = data.keterangan;
                    document.getElementById('form-keuangan').classList.remove('hidden');
                } else {
                    document.getElementById('a_project_id').value = data.project_id;
                    document.getElementById('a_project_label').textContent = data.project_label;
                    document.getElementById('a_tanggal').value = data.tanggal;
                    document.getElementById('a_waktu').value = data.waktu || '';
                    document.getElementById('a_keterangan').value = data.keterangan;
                    document.getElementById('form-aksi').classList.remove('hidden');
                }

                status.classList.remove('text-[#DC2626]');
                status.classList.add('text-[#16A34A]');
                status.textContent = 'Berhasil diusulkan - cek lagi sebelum simpan.';
                document.getElementById('ai-input').value = '';
            } catch (e) {
                status.textContent = 'Gagal menghubungi server.';
            } finally {
                btn.disabled = false;
            }
        }
    </script>
</x-app-layout>
