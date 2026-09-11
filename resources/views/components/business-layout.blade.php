<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#465FFF">

    <title>{{ isset($business) ? $business->nama_usaha.' — ' : '' }}SMARTS Business</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background: #F9FAFB; color: #101828; }
        .sbz-link { color: #475467; }
        .sbz-link:hover { background: #F9FAFB; color: #101828; }
        .sbz-link.active { background: #ECF3FF; color: #3641F5; font-weight: 500; }
    </style>
</head>
<body class="antialiased">
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r border-[#E4E7EC] flex-shrink-0 hidden md:flex flex-col">
            <div class="px-5 py-5 border-b border-[#E4E7EC]">
                <a href="{{ route('dashboard') }}" class="text-xs text-[#98A2B3] hover:text-[#465FFF]">← SMARTS Personal</a>
                <p class="text-[#101828] font-bold text-base mt-2">SMARTS <span class="text-[#465FFF]">Business</span></p>
            </div>

            @isset($business)
                <div class="px-5 py-4 border-b border-[#E4E7EC]">
                    <p class="text-[10px] text-[#98A2B3] uppercase tracking-wide">Business Aktif</p>
                    <p class="text-sm text-[#101828] font-semibold mt-1 truncate">{{ $business->nama_usaha }}</p>
                    <a href="{{ route('business.index') }}" class="text-[11px] text-[#465FFF] hover:underline">Ganti business</a>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-0.5 text-sm">
                    <a href="{{ route('business.dashboard', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.dashboard') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('business.journal.index', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.journal.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        Jurnal Umum
                    </a>
                    <a href="{{ route('business.coa.index', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.coa.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                        Chart of Account
                    </a>

                    <p class="text-[10px] text-[#98A2B3] uppercase tracking-wide px-3 pt-4 pb-1">Laporan</p>
                    <a href="{{ route('business.reports.neraca-saldo', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.reports.neraca-saldo') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 12V8M13 16v-4M8 20v-8"/></svg>
                        Neraca Saldo
                    </a>
                    <a href="{{ route('business.reports.neraca', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.reports.neraca') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 3v18"/></svg>
                        Neraca
                    </a>
                    <a href="{{ route('business.reports.laba-rugi', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.reports.laba-rugi') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 17l5-5 4 4 8-8"/></svg>
                        Laba Rugi
                    </a>
                    <a href="{{ route('business.tax.index', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.tax.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6M9 8h6M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/></svg>
                        Perhitungan Pajak
                    </a>

                    <p class="text-[10px] text-[#98A2B3] uppercase tracking-wide px-3 pt-4 pb-1">Lainnya</p>
                    <a href="{{ route('business.settings.edit', $business) }}" class="sbz-link flex items-center gap-3 px-3 py-2 rounded-lg {{ request()->routeIs('business.settings.*') ? 'active' : '' }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.87l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.87-.34 1.7 1.7 0 0 0-1 1.55V21a2 2 0 0 1-4 0v-.09a1.7 1.7 0 0 0-1-1.55 1.7 1.7 0 0 0-1.87.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.7 1.7 0 0 0 .34-1.87 1.7 1.7 0 0 0-1.55-1H3a2 2 0 0 1 0-4h.09a1.7 1.7 0 0 0 1.55-1 1.7 1.7 0 0 0-.34-1.87l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.7 1.7 0 0 0 1.87.34H9a1.7 1.7 0 0 0 1-1.55V3a2 2 0 0 1 4 0v.09a1.7 1.7 0 0 0 1 1.55 1.7 1.7 0 0 0 1.87-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.87V9a1.7 1.7 0 0 0 1.55 1H21a2 2 0 0 1 0 4h-.09a1.7 1.7 0 0 0-1.55 1Z"/></svg>
                        Pengaturan Bisnis
                    </a>
                </nav>
            @endisset

            <div class="px-5 py-4 border-t border-[#E4E7EC]">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-[#98A2B3] hover:text-[#465FFF] flex items-center gap-2">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Konten --}}
        <div class="flex-1 min-w-0">
            {{-- Top bar --}}
            <header class="bg-white border-b border-[#E4E7EC] px-4 md:px-8 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="javascript:history.back()" class="md:hidden text-[#667085]">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                    </a>
                    <h1 class="text-base md:text-lg font-semibold text-[#101828]">{{ $header ?? 'Business' }}</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="hidden sm:block text-xs text-[#667085]">{{ auth()->user()->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-[#ECF3FF] text-[#3641F5] flex items-center justify-center text-xs font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="max-w-6xl mx-auto px-4 md:px-8 py-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
