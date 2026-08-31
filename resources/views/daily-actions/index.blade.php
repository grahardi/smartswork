<x-app-layout>
    <x-slot name="header">Aksi Harian</x-slot>

    <div class="px-4 py-5">
        <div class="flex justify-end mb-3">
            <a href="{{ route('daily-actions.create') }}" class="text-sm font-medium text-white bg-[#3E5C4E] px-4 py-2 rounded-lg">+ Catat Aksi</a>
        </div>
        @if (session('status'))
            <div class="mb-4 text-sm text-[#3E5C4E] bg-[#EDF3EF] border border-[#CFE0D6] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-3">
            @forelse ($actions as $action)
                <div class="bg-white border border-[#EAE4D6] rounded-xl p-4 flex gap-3">
                    @if ($action->foto)
                        <img src="{{ Storage::url($action->foto) }}" alt="" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-medium text-[#2A2621]">
                                {{ $action->tanggal->translatedFormat('d M Y') }}
                                @if ($action->waktu) · {{ \Illuminate\Support\Carbon::parse($action->waktu)->format('H:i') }} @endif
                            </span>
                            <span class="text-[10px] text-[#8A8377] flex-shrink-0">{{ $action->project->workplace->nama }}</span>
                        </div>
                        <p class="text-sm text-[#2A2621] mt-1">{{ $action->keterangan }}</p>
                        <span class="text-[11px] text-[#3E5C4E] mt-1 inline-block">{{ $action->project->nama }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#6E675A] text-center py-8">Belum ada aksi harian yang dicatat.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $actions->links() }}
        </div>
    </div>
</x-app-layout>
