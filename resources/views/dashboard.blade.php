<x-app-layout>
    <x-slot name="header">SMARTS Work</x-slot>

    @php
        $user = auth()->user();
        $todayCount = $user->dailyActions()->whereDate('tanggal', now()->toDateString())->count();
        $menu = [
            ['route' => 'daily-actions.create', 'label' => 'Catat Aksi', 'color' => '#3E5C4E', 'icon' => 'pencil'],
            ['route' => 'daily-actions.index', 'label' => 'Aksi Harian', 'color' => '#B9832F', 'icon' => 'list'],
            ['route' => 'workplaces.index', 'label' => 'Tempat Kerja', 'color' => '#3F5C7A', 'icon' => 'briefcase'],
            ['route' => 'profile.create', 'label' => 'Data Diri', 'color' => '#7A5C3F', 'icon' => 'user'],
            ['route' => 'profile.edit', 'label' => 'Akun', 'color' => '#6E675A', 'icon' => 'settings'],
        ];
    @endphp

    <div class="px-4 py-5">

        <div class="bg-[#16231F] text-[#EDE7D9] rounded-xl p-4 mb-5 flex items-center justify-between">
            <div>
                <p class="text-xs text-[#9CAA9F]">Halo,</p>
                <p class="text-sm font-medium">{{ $user->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-[#9CAA9F]">Aksi hari ini</p>
                <p class="text-lg font-semibold">{{ $todayCount }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#3E5C4E] bg-[#EDF3EF] border border-[#CFE0D6] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-3 gap-3">
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#EAE4D6] rounded-xl">
                    <span class="w-11 h-11 rounded-full flex items-center justify-center" style="background: {{ $item['color'] }}1A; color: {{ $item['color'] }};">
                        @switch($item['icon'])
                            @case('pencil')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                @break
                            @case('list')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                @break
                            @case('briefcase')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                @break
                            @case('user')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
                                @break
                            @case('settings')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 0 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.7 1.7 0 0 0 .34-1.87 1.7 1.7 0 0 0-1.55-1H3a2 2 0 0 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.7 1.7 0 0 0 1.87.34H9a1.7 1.7 0 0 0 1-1.55V3a2 2 0 0 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87V9a1.7 1.7 0 0 0 1.55 1H21a2 2 0 0 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1Z"/></svg>
                                @break
                        @endswitch
                    </span>
                    <span class="text-[11px] text-[#2A2621] text-center leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="bg-white border border-[#EAE4D6] rounded-xl p-4 mt-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-sm">Aksi terakhir</h3>
                <a href="{{ route('daily-actions.index') }}" class="text-xs text-[#3E5C4E]">Lihat semua</a>
            </div>

            @php
                $recentActions = $user->dailyActions()->with('project.workplace')->orderByDesc('tanggal')->orderByDesc('waktu')->limit(5)->get();
            @endphp

            @forelse ($recentActions as $action)
                <div class="flex items-center justify-between py-2 border-b last:border-b-0 border-[#F0EBDF]">
                    <div class="min-w-0 pr-2">
                        <p class="text-sm text-[#2A2621] truncate">{{ $action->keterangan }}</p>
                        <p class="text-xs text-[#8A8377]">{{ $action->project->workplace->nama }} · {{ $action->project->nama }}</p>
                    </div>
                    <span class="text-xs text-[#8A8377] flex-shrink-0">{{ $action->tanggal->translatedFormat('d M') }}</span>
                </div>
            @empty
                <p class="text-sm text-[#6E675A]">Belum ada aksi harian.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
