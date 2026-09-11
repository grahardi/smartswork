<x-app-layout>
    <x-slot name="header">Catat Transaksi</x-slot>

    <div class="px-4 py-5">
        {{-- AI Assistant --}}
        <div class="bg-[#EFF6FF] border border-[#BFDBFE] rounded-xl p-4 mb-5">
            <div class="flex items-center gap-2 mb-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                <h3 class="text-sm font-semibold text-[#1F2333]">Catat dengan AI</h3>
            </div>
            <p class="text-xs text-[#7B7F99] mb-2">Ketik atau ngomong langsung, misal "bayar bensin 50rb" — AI usulkan kategori & jumlahnya.</p>
            <div class="flex gap-2">
                <input type="text" id="ai-input" placeholder="Contoh: bayar bensin 50rb" class="flex-1 rounded-lg border-[#BFDBFE] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                <button type="button" onclick="swkAiParseText()" id="ai-btn" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-lg">Proses</button>
                <button type="button" onclick="swkToggleRecord()" id="mic-btn" class="w-10 h-10 flex-shrink-0 rounded-full bg-white border border-[#BFDBFE] flex items-center justify-center">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v4"/></svg>
                </button>
            </div>
            <p id="ai-status" class="text-xs text-[#DC2626] mt-2"></p>
        </div>

        <form method="POST" action="{{ route('finance.transactions.store') }}" class="space-y-5">
            @csrf
            @include('finance.transactions._fields')

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan</x-primary-button>
                <a href="{{ route('finance.transactions.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>

    <script>
        async function swkAiParseText() {
            const teks = document.getElementById('ai-input').value.trim();
            if (!teks) return;
            await swkAiSend({ teks });
        }

        let mediaRecorder = null;
        let audioChunks = [];
        let isRecording = false;

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
                    status.classList.remove('text-[#DC2626]', 'text-[#16A34A]');
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

            const formData = new FormData();
            if (teks) formData.append('teks', teks);
            if (audio) formData.append('audio', audio, 'rekaman.webm');
            formData.append('tanggal_hari_ini', document.getElementById('tanggal').value || '');

            try {
                const res = await fetch('{{ route("finance.ai-parse") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData,
                });
                const data = await res.json();

                if (!res.ok) {
                    status.textContent = data.error || 'Gagal memproses.';
                    return;
                }

                document.getElementById('finance_category_id').value = data.finance_category_id;
                document.getElementById('jumlah').value = data.jumlah;
                document.getElementById('keterangan').value = data.keterangan;
                document.getElementById('tanggal').value = data.tanggal;

                status.classList.remove('text-[#DC2626]');
                status.classList.add('text-[#16A34A]');
                status.textContent = 'Berhasil: ' + data.kategori_label + ' - cek lagi sebelum simpan.';
                document.getElementById('ai-input').value = '';
            } catch (e) {
                status.textContent = 'Gagal menghubungi server.';
            } finally {
                btn.disabled = false;
            }
        }
    </script>
</x-app-layout>
