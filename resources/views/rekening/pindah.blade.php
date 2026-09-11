<x-app-layout>
    <x-slot name="header">Setor / Tarik Tunai</x-slot>

    <div class="px-4 py-5">
        @if ($rekenings->isEmpty())
            <p class="text-sm text-[#7B7F99]">
                Belum ada rekening. <a href="{{ route('rekening.create') }}" class="text-[#2563EB] underline">Tambah rekening dulu</a>.
            </p>
        @else
            <form method="POST" action="{{ route('rekening.pindah.store') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label value="Arah" />
                    <div class="mt-2 flex gap-4">
                        <label class="flex items-center gap-2 text-sm text-[#262135]">
                            <input type="radio" name="arah" value="setor" checked>
                            Setor Tunai (Cash → Rekening)
                        </label>
                        <label class="flex items-center gap-2 text-sm text-[#262135]">
                            <input type="radio" name="arah" value="tarik">
                            Tarik Tunai (Rekening → Cash)
                        </label>
                    </div>
                </div>

                <div>
                    <x-input-label for="bank_account_id" value="Rekening" />
                    <select id="bank_account_id" name="bank_account_id" required class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                        @foreach ($rekenings as $r)
                            <option value="{{ $r->id }}">{{ $r->nama_bank }} {{ $r->no_rekening ? '('.$r->no_rekening.')' : '' }} — Rp{{ number_format($r->saldoSekarang(), 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('bank_account_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="jumlah" value="Jumlah" />
                    <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" min="1" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="keterangan" value="Keterangan (opsional)" />
                    <x-text-input id="keterangan" name="keterangan" type="text" class="mt-1 block w-full" />
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <x-primary-button>Simpan</x-primary-button>
                    <a href="{{ route('rekening.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
