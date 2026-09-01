<x-app-layout>
    <x-slot name="header">Transfer ke Teman</x-slot>

    <div class="px-4 py-5">
        @if ($friends->isEmpty())
            <p class="text-sm text-[#7B7F99]">
                Kamu belum punya teman. <a href="{{ route('friends.index') }}" class="text-[#2563EB] underline">Tambah teman dulu</a> sebelum bisa transfer.
            </p>
        @else
            <form method="POST" action="{{ route('finance.transfer.store') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="to_user_id" value="Kirim ke" />
                    <select id="to_user_id" name="to_user_id" required
                        class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
                        <option value="">Pilih teman</option>
                        @foreach ($friends as $friend)
                            <option value="{{ $friend->id }}" @selected(old('to_user_id') == $friend->id)>{{ $friend->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('to_user_id')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="jumlah" value="Jumlah (Rp)" />
                    <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" min="1" class="mt-1 block w-full"
                        value="{{ old('jumlah') }}" required />
                    <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="keterangan" value="Keterangan (opsional)" />
                    <textarea id="keterangan" name="keterangan" rows="2"
                        class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('keterangan') }}</textarea>
                    <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                </div>

                <p class="text-[11px] text-[#9CA3AF]">
                    Jumlah ini akan tercatat sebagai pengeluaran di akunmu dan pemasukan di akun teman yang dipilih.
                </p>

                <div class="flex items-center gap-4 pt-2">
                    <x-primary-button>Kirim Transfer</x-primary-button>
                    <a href="{{ route('finance.transactions.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
