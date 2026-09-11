<x-business-layout :business="$business">
    <x-slot name="header">{{ $business->nama_usaha }}</x-slot>

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="bg-[#101828] text-white rounded-2xl p-5 mb-5">
            <p class="text-xs text-white/50">Business</p>
            <p class="text-lg font-semibold swk-heading">{{ $business->nama_usaha }}</p>
            <div class="flex gap-6 mt-3">
                <div>
                    <p class="text-[11px] text-white/50">Jumlah Akun</p>
                    <p class="text-sm font-medium">{{ $totalAkun }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-white/50">Jurnal Bulan Ini</p>
                    <p class="text-sm font-medium">{{ $totalJurnalBulanIni }}</p>
                </div>
            </div>
        </div>

        @php
            $menu = [
                ['route' => 'business.bot', 'label' => 'Bot AI', 'color' => '#465FFF', 'icon' => 'bot'],
                ['route' => 'business.journal.create', 'label' => 'Catat Jurnal', 'color' => '#465FFF', 'icon' => 'pencil'],
                ['route' => 'business.journal.index', 'label' => 'Jurnal Umum', 'color' => '#DBA83B', 'icon' => 'book'],
                ['route' => 'business.coa.index', 'label' => 'Chart of Account', 'color' => '#3E9B93', 'icon' => 'list'],
                ['route' => 'business.reports.neraca-saldo', 'label' => 'Neraca Saldo', 'color' => '#3F5C7A', 'icon' => 'chart-bar'],
                ['route' => 'business.reports.neraca', 'label' => 'Neraca', 'color' => '#D6549E', 'icon' => 'scale'],
                ['route' => 'business.reports.laba-rugi', 'label' => 'Laba Rugi', 'color' => '#10B981', 'icon' => 'trend-up'],
                ['route' => 'business.tax.index', 'label' => 'Perhitungan Pajak', 'color' => '#B54708', 'icon' => 'file-check'],
                ['route' => 'business.tax.kalkulator', 'label' => 'Kalkulator Pajak', 'color' => '#B54708', 'icon' => 'calculator'],
            ];
        @endphp

        <div class="grid grid-cols-3 gap-3">
            @foreach ($menu as $item)
                <a href="{{ route($item['route'], $business) }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#E4E7EC] rounded-xl text-center">
                    <span class="w-10 h-10 rounded-full flex items-center justify-center" style="background: {{ $item['color'] }}1A; color: {{ $item['color'] }};">
                        @switch($item['icon'])
                            @case('bot')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M12 8V4M9 4h6"/><circle cx="9" cy="14" r="1"/><circle cx="15" cy="14" r="1"/></svg>
                                @break
                            @case('pencil')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                @break
                            @case('book')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                                @break
                            @case('list')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                                @break
                            @case('chart-bar')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 3v18h18"/><path d="M18 12V8M13 16v-4M8 20v-8"/></svg>
                                @break
                            @case('scale')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 3v18"/></svg>
                                @break
                            @case('trend-up')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 17l5-5 4 4 8-8"/></svg>
                                @break
                            @case('file-check')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 12h6m-6 4h6M9 8h6M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/></svg>
                                @break
                            @case('calculator')
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M8 6h8M8 10h2M12 10h2M16 10h2M8 14h2M12 14h2M16 14h2M8 18h2M12 18h2M16 18h2"/></svg>
                                @break
                        @endswitch
                    </span>
                    <span class="text-[11px] text-[#1F2333] leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        <a href="{{ route('business.index') }}" class="text-xs text-[#667085] mt-5 inline-block">← Ganti Business</a>
    </div>
</x-business-layout>
