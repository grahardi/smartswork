<x-app-layout>
    <x-slot name="header">Aksi Harian — {{ $friend->name }}</x-slot>

    <div class="px-4 py-5">
        <div class="mb-3 text-xs text-[#8A8377] bg-[#F5F6FD] border border-[#E5E7F5] rounded-lg px-3 py-2">
            👁 Mode lihat saja (read-only) — data milik {{ $friend->name }}.
        </div>

        @php $palette = ['#FFC9E9', '#F5F2B8', '#D3E8E8']; @endphp

        <div class="space-y-3">
            @forelse ($actions as $i => $action)
                <div class="rounded-2xl p-4 flex gap-3" style="background: {{ $palette[$i % 3] }};">
                    @if ($action->foto)
                        <img src="{{ Storage::url($action->foto) }}" alt="" onclick="swkZoom(this.src)"
                            class="w-14 h-14 rounded-xl object-cover flex-shrink-0 border-2 border-white cursor-zoom-in">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-white/50 flex-shrink-0"></div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-semibold text-[#262135] swk-heading">
                                {{ $action->tanggal->translatedFormat('d M Y') }}
                                @if ($action->waktu) · {{ \Illuminate\Support\Carbon::parse($action->waktu)->format('H:i') }} @endif
                            </span>
                            <span class="text-[10px] text-[#262135]/60 flex-shrink-0">{{ $action->project->workplace->nama }}</span>
                        </div>
                        <p class="text-sm text-[#262135] mt-1">{{ $action->keterangan }}</p>
                        <span class="text-[11px] text-[#262135]/70 font-medium mt-1 inline-block">{{ $action->project->nama }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99] text-center py-8">Belum ada aksi harian.</p>
            @endforelse
        </div>

        <div class="mt-4">{{ $actions->links() }}</div>
    </div>
</x-app-layout>
