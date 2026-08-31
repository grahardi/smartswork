<x-app-layout>
    <x-slot name="header">Edit Tempat Kerja</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('workplaces.update', $workplace) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="nama" value="Nama Tempat Kerja" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" value="{{ old('nama', $workplace->nama) }}" required autofocus />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="jabatan" value="Jabatan Kamu di Sini" />
                <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" value="{{ old('jabatan', $pivot->jabatan ?? '') }}" />
                <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="alamat" value="Alamat" />
                <textarea id="alamat" name="alamat" rows="2"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('alamat', $workplace->alamat) }}</textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan" value="Keterangan (opsional)" />
                <textarea id="keterangan" name="keterangan" rows="2"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">{{ old('keterangan', $workplace->keterangan) }}</textarea>
                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
            </div>

            <x-koordinat-input :latitude="$workplace->latitude" :longitude="$workplace->longitude" />

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Simpan Perubahan</x-primary-button>
                <a href="{{ route('workplaces.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
