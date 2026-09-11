<x-business-layout>
    <x-slot name="header">Daftarkan Business</x-slot>

    <div class="px-4 py-5">
        <p class="text-xs text-[#667085] mb-4">
            Setelah dibuat, Chart of Account standar (Aset, Kewajiban, Ekuitas, Pendapatan, Beban) otomatis disiapkan supaya bisa langsung mulai jurnal.
        </p>

        <form method="POST" action="{{ route('business.store') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="nama_usaha" value="Nama Usaha" />
                <x-text-input id="nama_usaha" name="nama_usaha" type="text" class="mt-1 block w-full" value="{{ old('nama_usaha') }}" required autofocus />
                <x-input-error :messages="$errors->get('nama_usaha')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jenis_usaha" value="Jenis Usaha (opsional)" />
                <x-text-input id="jenis_usaha" name="jenis_usaha" type="text" class="mt-1 block w-full" value="{{ old('jenis_usaha') }}" placeholder="Contoh: Jasa, Dagang, Manufaktur" />
                <x-input-error :messages="$errors->get('jenis_usaha')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="npwp" value="NPWP (opsional)" />
                <x-text-input id="npwp" name="npwp" type="text" class="mt-1 block w-full" value="{{ old('npwp') }}" />
                <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="alamat" value="Alamat (opsional)" />
                <textarea id="alamat" name="alamat" rows="2"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('alamat') }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <x-primary-button>Daftarkan Business</x-primary-button>
        </form>
    </div>
</x-business-layout>
