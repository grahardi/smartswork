<x-app-layout>
    <x-slot name="header">Kategori Keuangan</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('finance.categories.create') }}" class="text-sm font-medium text-white bg-[#3E5C4E] px-4 py-2 rounded-lg">+ Tambah Kategori</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#3E5C4E] bg-[#EDF3EF] border border-[#CFE0D6] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-[#A32D2D] bg-[#FCEBEB] border border-[#F3CFCF] rounded-lg px-4 py-3">{{ session('error') }}</div>
        @endif

        <div class="mb-4">
            <p class="text-xs font-medium text-[#8A8377] mb-2">PEMASUKAN</p>
            <div class="space-y-2">
                @forelse ($categories->where('type', 'pemasukan') as $category)
                    <div class="bg-white border border-[#EAE4D6] rounded-lg px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background: {{ $category->warna }}"></span>
                            <span class="text-sm text-[#2A2621] truncate">{{ $category->nama }}</span>
                            <span class="text-[11px] text-[#8A8377] flex-shrink-0">({{ $category->transactions_count }})</span>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <a href="{{ route('finance.categories.edit', $category) }}" class="text-xs text-[#3F5C7A]">Edit</a>
                            <form method="POST" action="{{ route('finance.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-[#A32D2D]">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#8A8377]">Belum ada kategori pemasukan.</p>
                @endforelse
            </div>
        </div>

        <div>
            <p class="text-xs font-medium text-[#8A8377] mb-2">PENGELUARAN</p>
            <div class="space-y-2">
                @forelse ($categories->where('type', 'pengeluaran') as $category)
                    <div class="bg-white border border-[#EAE4D6] rounded-lg px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background: {{ $category->warna }}"></span>
                            <span class="text-sm text-[#2A2621] truncate">{{ $category->nama }}</span>
                            <span class="text-[11px] text-[#8A8377] flex-shrink-0">({{ $category->transactions_count }})</span>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <a href="{{ route('finance.categories.edit', $category) }}" class="text-xs text-[#3F5C7A]">Edit</a>
                            <form method="POST" action="{{ route('finance.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-[#A32D2D]">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#8A8377]">Belum ada kategori pengeluaran.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
