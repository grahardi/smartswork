<x-app-layout>
    <x-slot name="header">Kategori Keuangan</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('finance.categories.create') }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-lg">+ Tambah Kategori</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 text-sm text-[#DC2626] bg-[#FEE2E2] border border-[#F3CFCF] rounded-lg px-4 py-3">{{ session('error') }}</div>
        @endif

        <div class="mb-4">
            <p class="text-xs font-medium text-[#9CA3AF] mb-2">PEMASUKAN</p>
            <div class="space-y-2">
                @forelse ($categories->where('type', 'pemasukan') as $category)
                    <div class="bg-white border border-[#E7E9F5] rounded-lg px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background: {{ $category->warna }}"></span>
                            <span class="text-sm text-[#1F2333] truncate">{{ $category->nama }}</span>
                            <span class="text-[11px] text-[#9CA3AF] flex-shrink-0">({{ $category->transactions_count }})</span>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <a href="{{ route('finance.categories.edit', $category) }}" class="text-xs text-[#2563EB]">Edit</a>
                            <form method="POST" action="{{ route('finance.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-[#DC2626]">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#9CA3AF]">Belum ada kategori pemasukan.</p>
                @endforelse
            </div>
        </div>

        <div>
            <p class="text-xs font-medium text-[#9CA3AF] mb-2">PENGELUARAN</p>
            <div class="space-y-2">
                @forelse ($categories->where('type', 'pengeluaran') as $category)
                    <div class="bg-white border border-[#E7E9F5] rounded-lg px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-3 h-3 rounded-full flex-shrink-0" style="background: {{ $category->warna }}"></span>
                            <span class="text-sm text-[#1F2333] truncate">{{ $category->nama }}</span>
                            <span class="text-[11px] text-[#9CA3AF] flex-shrink-0">({{ $category->transactions_count }})</span>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <a href="{{ route('finance.categories.edit', $category) }}" class="text-xs text-[#2563EB]">Edit</a>
                            <form method="POST" action="{{ route('finance.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ $category->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-[#DC2626]">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#9CA3AF]">Belum ada kategori pengeluaran.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
