<x-app-layout>
    <x-slot name="header">Galeri</x-slot>

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('gallery.store') }}" enctype="multipart/form-data" class="bg-white border border-[#E7E9F5] rounded-xl p-4 mb-5">
            @csrf
            <x-input-label value="Upload Foto (bisa lebih dari satu, maks 10MB/foto)" />
            <input type="file" name="foto[]" accept="image/*" multiple
                class="mt-2 block w-full text-sm text-[#6E675A]">
            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
            <x-input-error :messages="$errors->get('foto.*')" class="mt-2" />
            <input type="text" name="keterangan" placeholder="Keterangan (opsional, berlaku untuk semua foto yang diupload)"
                class="mt-2 block w-full rounded-lg border-[#E5E7F5] focus:border-[#2563EB] focus:ring-[#2563EB] text-sm">
            <button type="submit" class="mt-3 text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-lg">Upload</button>
        </form>

        <div class="grid grid-cols-3 gap-2">
            @forelse ($photos as $photo)
                <div class="relative group">
                    <img src="{{ Storage::url($photo->path) }}" alt="" class="w-full aspect-square object-cover rounded-lg">
                    <form method="POST" action="{{ route('gallery.destroy', $photo) }}" onsubmit="return confirm('Hapus foto ini?')"
                        class="absolute top-1 right-1">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-6 h-6 rounded-full bg-black/50 text-white text-xs flex items-center justify-center">✕</button>
                    </form>
                </div>
            @empty
                <p class="col-span-3 text-sm text-[#7B7F99] text-center py-8">Belum ada foto di galeri.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $photos->links() }}</div>
    </div>
</x-app-layout>
