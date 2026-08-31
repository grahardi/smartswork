<x-app-layout>
    <x-slot name="header">Tempat Kerja</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('workplaces.create') }}" class="text-sm font-medium text-white bg-[#3E5C4E] px-4 py-2 rounded-lg">+ Tambah</a>
        </div>
        @if (session('status'))
            <div class="mb-4 text-sm text-[#3E5C4E] bg-[#EDF3EF] border border-[#CFE0D6] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($workplaces as $workplace)
                <a href="{{ route('workplaces.projects.index', $workplace) }}"
                   class="block bg-white border border-[#EAE4D6] rounded-xl p-4">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-[#2A2621] text-sm">{{ $workplace->nama }}</h3>
                        @if ($workplace->is_default)
                            <span class="text-[10px] bg-[#F3E4CB] text-[#8A5F1F] px-2 py-0.5 rounded-full">Default</span>
                        @endif
                    </div>
                    <p class="text-xs text-[#6E675A] mt-1">
                        {{ $workplace->pivot->jabatan ?? '—' }}
                        @if ($workplace->alamat) · {{ $workplace->alamat }} @endif
                    </p>
                    <p class="text-xs text-[#3E5C4E] mt-2">{{ $workplace->projects_count }} project →</p>
                </a>
            @empty
                <p class="text-sm text-[#6E675A] text-center py-8">Belum ada tempat kerja.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
