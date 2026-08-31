<x-app-layout>
    <x-slot name="header">Tempat Tinggal</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('residences.create') }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-full">+ Tambah</a>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="space-y-3">
            @forelse ($residences as $residence)
                <div class="bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-sm text-[#262135] swk-heading">{{ $residence->label }}</h3>
                            @if ($residence->is_default)
                                <span class="text-[10px] bg-[#FEF3C7] text-[#92400E] px-2 py-0.5 rounded-full">Utama</span>
                            @endif
                        </div>
                        @if ($residence->hasCoordinates())
                            <span class="text-[10px] text-[#2563EB]">📍 berkoordinat</span>
                        @endif
                    </div>
                    @if ($residence->alamat)
                        <p class="text-xs text-[#7B7F99] mt-1">{{ $residence->alamat }}</p>
                    @endif
                    @if ($residence->hasCoordinates())
                        <p class="text-[11px] text-[#9CA3AF] mt-1">{{ $residence->latitude }}, {{ $residence->longitude }}</p>
                    @endif

                    <div class="flex items-center gap-3 mt-3">
                        <a href="{{ route('residences.edit', $residence) }}" class="text-xs text-[#2563EB] font-medium">Edit</a>
                        @unless ($residence->is_default)
                            <form method="POST" action="{{ route('residences.make-default', $residence) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-[#7B7F99] font-medium">Jadikan Utama</button>
                            </form>
                        @endunless
                        <form method="POST" action="{{ route('residences.destroy', $residence) }}" onsubmit="return confirm('Hapus {{ $residence->label }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-[#DC2626] font-medium">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada tempat tinggal tercatat.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
