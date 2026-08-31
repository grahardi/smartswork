<x-app-layout>
    <x-slot name="header">Tempat Kerja</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('workplaces.create') }}" class="text-sm font-medium text-white bg-[#2563EB] px-4 py-2 rounded-lg">+ Tambah</a>
        </div>
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($workplaces as $workplace)
                <a href="{{ route('workplaces.projects.index', $workplace) }}"
                   class="block bg-white border border-[#E7E9F5] rounded-xl p-4">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-[#1F2333] text-sm">{{ $workplace->nama }}</h3>
                        @if ($workplace->is_default)
                            <span class="text-[10px] bg-[#FEF3C7] text-[#92400E] px-2 py-0.5 rounded-full">Default</span>
                        @endif
                    </div>
                    <p class="text-xs text-[#7B7F99] mt-1">
                        {{ $workplace->pivot->jabatan ?? '—' }}
                        @if ($workplace->alamat) · {{ $workplace->alamat }} @endif
                    </p>
                    <p class="text-xs text-[#2563EB] mt-2">{{ $workplace->projects_count }} project →</p>
                </a>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada tempat kerja.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
