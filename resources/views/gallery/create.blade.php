<x-app-layout>
    <x-slot name="header">Upload Foto</x-slot>

    <div class="px-4 py-5">
        <form method="POST" action="{{ route('gallery.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <x-input-label value="Pilih Foto (bisa lebih dari satu, maks 10MB/foto)" />
                <input type="file" name="foto[]" accept="image/*" multiple required
                    class="mt-2 block w-full text-sm text-[#6E675A]">
                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                <x-input-error :messages="$errors->get('foto.*')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="keterangan" value="Keterangan (opsional, berlaku untuk semua foto)" />
                <input id="keterangan" type="text" name="keterangan"
                    class="mt-1 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-primary-button>Upload</x-primary-button>
                <a href="{{ route('gallery.index') }}" class="text-sm text-[#7B7F99]">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
