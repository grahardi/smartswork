<x-business-layout :business="$business">
    <x-slot name="header">{{ $business->nama_usaha }}</x-slot>

    <div class="px-4 py-5">
        @if (session('status'))
            <div class="mb-4 text-sm text-[#2563EB] bg-[#EFF6FF] border border-[#BFDBFE] rounded-lg px-4 py-3">{{ session('status') }}</div>
        @endif

        <div class="bg-[#262135] text-white rounded-2xl p-5 mb-5">
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
                ['route' => 'business.journal.create', 'label' => 'Catat Jurnal', 'color' => '#2563EB'],
                ['route' => 'business.journal.index', 'label' => 'Jurnal Umum', 'color' => '#DBA83B'],
                ['route' => 'business.coa.index', 'label' => 'Chart of Account', 'color' => '#3E9B93'],
                ['route' => 'business.reports.neraca-saldo', 'label' => 'Neraca Saldo', 'color' => '#3F5C7A'],
                ['route' => 'business.reports.neraca', 'label' => 'Neraca', 'color' => '#D6549E'],
                ['route' => 'business.reports.laba-rugi', 'label' => 'Laba Rugi', 'color' => '#10B981'],
            ];
        @endphp

        <div class="grid grid-cols-3 gap-3">
            @foreach ($menu as $item)
                <a href="{{ route($item['route'], $business) }}" class="flex flex-col items-center gap-2 py-4 bg-white border border-[#E7E9F5] rounded-xl text-center">
                    <span class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold" style="background: {{ $item['color'] }}1A; color: {{ $item['color'] }};">
                        {{ substr($item['label'], 0, 1) }}
                    </span>
                    <span class="text-[11px] text-[#1F2333] leading-tight">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>

        <a href="{{ route('business.index') }}" class="text-xs text-[#7B7F99] mt-5 inline-block">← Ganti Business</a>
    </div>
</x-business-layout>
