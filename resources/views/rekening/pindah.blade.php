<x-app-layout>
    <x-slot name="header">Pindah Saldo</x-slot>

    <div class="px-4 py-5">
        <p class="text-xs text-[#7B7F99] mb-4">Pindahkan saldo antar Cash, Bank, atau E-Wallet. Total kekayaan tidak berubah, cuma pindah kantong.</p>

        <form method="POST" action="{{ route('rekening.pindah.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="dari" value="Dari" />
                <select id="dari" name="dari" required class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                    <option value="cash">💵 Cash</option>
                    @foreach ($rekenings as $r)
                        <option value="{{ $r->id }}">{{ $r->jenis === 'ewallet' ? '📱' : '🏦' }} {{ $r->nama_bank }} — Rp{{ number_format($r->saldoSekarang(), 0, ',', '.') }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('dari')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="ke" value="Ke" />
                <select id="ke" name="ke" required class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                    <option value="cash">💵 Cash</option>
                    @foreach ($rekenings as $r)
                        <option value="{{ $r->id }}">{{ $r->jenis === 'ewallet' ? '📱' : '🏦' }} {{ $r->nama_bank }} — Rp{{ number_format($r->saldoSekarang(), 0, ',', '.') }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('ke')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jumlah" value="Jumlah" />
                <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" min="1" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan" value="Keterangan (opsional)" />
                <x-text-input id="keterangan" name="keterangan" type="text" class="mt-1 block w-full" placeholder="Contoh: Top up OVO dari BCA" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Pindahkan</x-primary-button>
                <a href="{{ route('rekening.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
