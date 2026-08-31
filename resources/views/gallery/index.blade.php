<x-app-layout>
    <x-slot name="header">Galeri</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('gallery.create') }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-full">+ Tambah</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="grid grid-cols-3 gap-2">
            @forelse ($photos as $photo)
                <div class="relative group">
                    <img src="{{ Storage::url($photo->path) }}" alt="" onclick="swkZoom(this.src)"
                        class="w-full aspect-square object-cover rounded-lg cursor-zoom-in">
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
