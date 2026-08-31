<x-app-layout>
    <x-slot name="header">SMARTS Work</x-slot>

    @php
        $user = auth()->user();
        $todayCount = $user->dailyActions()->whereDate('tanggal', now()->toDateString())->count();
        $menu = [
            ['route' => 'daily-actions.create', 'label' => 'Catat Aksi', 'color' => '#4F46E5', 'icon' => 'pencil'],
            ['route' => 'daily-actions.index', 'label' => 'Aksi Harian', 'color' => '#F59E0B', 'icon' => 'list'],
            ['route' => 'finance.transactions.index', 'label' => 'Keuangan', 'color' => '#10B981', 'icon' => 'wallet'],
            ['route' => 'workplaces.index', 'label' => 'Tempat Kerja', 'color' => '#2563EB', 'icon' => 'briefcase'],
            ['route' => 'profile.create', 'label' => 'Data Diri', 'color' => '#EA580C', 'icon' => 'user'],
            ['route' => 'profile.edit', 'label' => 'Akun', 'color' => '#7B7F99', 'icon' => 'settings'],
        ];
    @endphp

    <div class="px-4 py-5">

        <div class="bg-gradient-to-br from-[#4F46E5] to-[#6366F1] text-white rounded-2xl p-4 mb-5 flex items-center justify-between shadow-md shadow-indigo-200">
            <div>
                <p class="text-xs text-indigo-100">Halo,</p>
                <p class="text-sm font-medium">{{ $user->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-indigo-100">Aksi hari ini</p>
                <p class="text-lg font-semibold">{{ $todayCount }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#4F46E5] bg-[#EEF2FF] border border-[#C7D2FE] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-3 gap-3">
            @foreach ($menu as $item)
                <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#E7E9F5] rounded-xl">
                    <span class="w-11 h-11 rounded-full flex items-center justify-center" style="background: {{ $item['color'] }}1A; color: {{ $item['color'] }};">
                        @switch($item['icon'])
                            @case('pencil')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                @break
                            @case('list')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                @break
                            @case('wallet')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"/><path d="M17 12h4v3h-4a1.5 1.5 0 0 1 0-3Z"/></svg>
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
                    <span class="text-[11px] text-[#1F2333] text-center leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 mt-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-sm">Aksi terakhir</h3>
                <a href="{{ route('daily-actions.index') }}" class="text-xs text-[#4F46E5]">Lihat semua</a>
            </div>

            @php
                $recentActions = $user->dailyActions()->with('project.workplace')->orderByDesc('tanggal')->orderByDesc('waktu')->limit(5)->get();
            @endphp

            @forelse ($recentActions as $action)
                <div class="flex items-center justify-between py-2 border-b last:border-b-0 border-[#EEF0FA]">
                    <div class="min-w-0 pr-2">
                        <p class="text-sm text-[#1F2333] truncate">{{ $action->keterangan }}</p>
                        <p class="text-xs text-[#9CA3AF]">{{ $action->project->workplace->nama }} · {{ $action->project->nama }}</p>
                    </div>
                    <span class="text-xs text-[#9CA3AF] flex-shrink-0">{{ $action->tanggal->translatedFormat('d M') }}</span>
                </div>
            @empty
                <p class="text-sm text-[#7B7F99]">Belum ada aksi harian.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
