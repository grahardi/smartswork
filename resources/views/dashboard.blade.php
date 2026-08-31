<x-app-layout>
    <x-slot name="header">Beranda</x-slot>

    @php
        $user = auth()->user();
        $todayCount = $user->dailyActions()->whereDate('tanggal', now()->toDateString())->count();
        $workplaceCount = $user->workplaces()->count();
        $recentActions = $user->dailyActions()->with('project.workplace')->orderByDesc('tanggal')->orderByDesc('waktu')->limit(5)->get();
    @endphp

    <div class="px-4 py-5 space-y-5">
        @if (session('status'))
            <div class="text-sm text-[#3E5C4E] bg-[#EDF3EF] border border-[#CFE0D6] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-3 gap-3">
            <a href="{{ route('daily-actions.create') }}" class="bg-white border border-[#EAE4D6] rounded-xl p-3">
                <p class="text-[10px] text-[#8A8377]">Hari ini</p>
                <p class="text-xl font-semibold text-[#2A2621] mt-1">{{ $todayCount }}</p>
            </a>
            <a href="{{ route('workplaces.index') }}" class="bg-white border border-[#EAE4D6] rounded-xl p-3">
                <p class="text-[10px] text-[#8A8377]">Tempat kerja</p>
                <p class="text-xl font-semibold text-[#2A2621] mt-1">{{ $workplaceCount }}</p>
            </a>
            <a href="{{ route('daily-actions.index') }}" class="bg-white border border-[#EAE4D6] rounded-xl p-3">
                <p class="text-[10px] text-[#8A8377]">Jurnal</p>
                <p class="text-xl font-semibold text-[#3E5C4E] mt-1">→</p>
            </a>
        </div>

        <div class="bg-white border border-[#EAE4D6] rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-[#2A2621] text-sm">Aksi terakhir</h3>
                <a href="{{ route('daily-actions.index') }}" class="text-xs text-[#3E5C4E]">Lihat semua</a>
            </div>

            @forelse ($recentActions as $action)
                <div class="flex items-center justify-between py-2 border-b last:border-b-0 border-[#F0EBDF]">
                    <div class="min-w-0 pr-2">
                        <p class="text-sm text-[#2A2621] truncate">{{ $action->keterangan }}</p>
                        <p class="text-xs text-[#8A8377]">{{ $action->project->workplace->nama }} · {{ $action->project->nama }}</p>
                    </div>
                    <span class="text-xs text-[#8A8377] flex-shrink-0">{{ $action->tanggal->translatedFormat('d M') }}</span>
                </div>
            @empty
                <p class="text-sm text-[#6E675A]">Belum ada aksi harian. <a href="{{ route('daily-actions.create') }}" class="text-[#3E5C4E] underline">Catat yang pertama</a>.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
