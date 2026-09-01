<x-app-layout>
    <x-slot name="header">SMARTS Work</x-slot>

    @php
        $user = auth()->user();
        $todayCount = $user->dailyActions()->whereDate('tanggal', now()->toDateString())->count();
        $menuGroups = [
            'Tools' => [
                ['route' => 'daily-actions.create', 'label' => 'Catat Aksi', 'color' => '#2563EB', 'icon' => 'pencil'],
                ['route' => 'daily-actions.index', 'label' => 'Aksi Harian', 'color' => '#DBA83B', 'icon' => 'list'],
                ['route' => 'calendar.index', 'label' => 'Calendar', 'color' => '#DC2626', 'icon' => 'calendar'],
                ['route' => 'finance.transactions.index', 'label' => 'Keuangan', 'color' => '#3E9B93', 'icon' => 'wallet'],
                ['route' => 'notes.index', 'label' => 'Coretan', 'color' => '#DBA83B', 'icon' => 'note'],
                ['route' => 'gallery.index', 'label' => 'Galeri', 'color' => '#D6549E', 'icon' => 'gallery'],
            ],
            'Data Personal' => [
                ['route' => 'workplaces.index', 'label' => 'Tempat Kerja', 'color' => '#262135', 'icon' => 'briefcase'],
                ['route' => 'residences.index', 'label' => 'Tempat Tinggal', 'color' => '#D6549E', 'icon' => 'home'],
                ['route' => 'profile.create', 'label' => 'Data Diri', 'color' => '#7B7F99', 'icon' => 'user'],
                ['route' => 'friends.index', 'label' => 'Teman', 'color' => '#3B82F6', 'icon' => 'friends'],
                ['route' => 'profile.edit', 'label' => 'Akun', 'color' => '#5A556B', 'icon' => 'settings'],
            ],
        ];
    @endphp

    <div class="px-4 py-5">

        <div class="bg-[#262135] text-white rounded-2xl p-5 mb-5 relative overflow-hidden">
            <div class="absolute -left-10 -top-10 w-32 h-32 rounded-full bg-white/5"></div>
            <p class="relative text-[11px] text-white/50 mb-3" id="swk-clock">
                {{ now()->translatedFormat('l, d F Y') }} · {{ now()->format('H:i') }} WIB
            </p>
            <div class="relative flex items-center justify-between mb-4">
                <p class="text-lg font-semibold swk-heading leading-tight">Hi!,<br>{{ explode(' ', $user->name)[0] }}</p>
                <div class="w-11 h-11 rounded-full bg-[#FFC9E9] flex items-center justify-center text-[#262135] font-semibold overflow-hidden flex-shrink-0">
                    @if ($user->profile?->foto_profil)
                        <img src="{{ Storage::url($user->profile->foto_profil) }}" alt="" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
            </div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[11px] text-white/50">Aksi hari ini</p>
                    <p class="text-sm font-medium">{{ $todayCount }} tercatat</p>
                </div>
                <a href="{{ route('daily-actions.create') }}" class="bg-[#2563EB] text-white text-xs font-semibold px-4 py-2 rounded-full">
                    + Catat
                </a>
            </div>
        </div>

        <script>
            (function () {
                const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                function tick() {
                    const now = new Date();
                    const teks = hari[now.getDay()] + ', ' + now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear()
                        + ' · ' + String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0') + ':' + String(now.getSeconds()).padStart(2,'0') + ' WIB';
                    const el = document.getElementById('swk-clock');
                    if (el) el.textContent = teks;
                }
                tick();
                setInterval(tick, 1000);
            })();
        </script>

        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        @foreach ($menuGroups as $groupLabel => $items)
            <p class="text-xs font-medium text-[#8A8377] mb-2 {{ $loop->first ? '' : 'mt-6' }}">{{ strtoupper($groupLabel) }}</p>
            <div class="grid grid-cols-3 gap-3">
                @foreach ($items as $item)
                    <a href="{{ route($item['route']) }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#E7E9F5] rounded-xl">
                        <span class="w-11 h-11 rounded-full flex items-center justify-center" style="background: {{ $item['color'] }}1A; color: {{ $item['color'] }};">
                            @switch($item['icon'])
                                @case('pencil')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    @break
                                @case('list')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                    @break
                                @case('note')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                    @break
                                @case('gallery')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                    @break
                                @case('friends')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3"/><path d="M2 20c0-3.5 3-5.5 7-5.5s7 2 7 5.5"/><circle cx="17" cy="9" r="2.5"/><path d="M16.5 14.5c2.5 0 5 1.5 5.5 4"/></svg>
                                    @break
                                @case('home')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>
                                    @break
                                @case('calendar')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
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
        @endforeach

        <div class="bg-white border border-[#E7E9F5] rounded-xl p-4 mt-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-sm">Aksi terakhir</h3>
                <a href="{{ route('daily-actions.index') }}" class="text-xs text-[#2563EB]">Lihat semua</a>
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
