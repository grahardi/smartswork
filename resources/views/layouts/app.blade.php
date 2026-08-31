<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#16231F">

    <title>{{ config('app.name', 'SMARTS Work') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@500;600&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; background: #F6F1E7; }
        .swk-brandfont { font-family: 'Newsreader', serif; }
    </style>
</head>
<body class="antialiased pb-20">

    @auth
        @if (auth()->user()->is_demo)
            <div class="bg-[#B9832F] text-[#16231F] text-center text-xs font-medium py-2 px-4">
                Akun demo (read-only) — perubahan tidak disimpan.
            </div>
        @endif
    @endauth

    @if (session('demo_blocked'))
        <div class="bg-[#FCEBEB] text-[#791F1F] text-center text-xs py-2 px-4">
            {{ session('demo_blocked') }}
        </div>
    @endif

    {{-- Top bar --}}
    <header class="sticky top-0 z-30 bg-[#16231F] text-[#EDE7D9]">
        <div class="max-w-lg mx-auto px-4 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="swk-brandfont text-[17px] font-medium">SMARTS Work</span>
            </div>
            @isset($header)
                <span class="text-xs text-[#9CAA9F]">{{ $header }}</span>
            @endisset
        </div>
    </header>

    {{-- Konten --}}
    <main class="max-w-lg mx-auto">
        {{ $slot }}
    </main>

    {{-- Bottom navigation --}}
    <nav class="fixed bottom-0 inset-x-0 z-30 bg-white border-t border-[#DAD4C4]" style="padding-bottom: env(safe-area-inset-bottom);">
        <div class="max-w-lg mx-auto grid grid-cols-4">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home'],
                    ['route' => 'daily-actions.index', 'label' => 'Aksi', 'icon' => 'pencil'],
                    ['route' => 'workplaces.index', 'label' => 'Kerja', 'icon' => 'briefcase'],
                    ['route' => 'profile.edit', 'label' => 'Profil', 'icon' => 'user'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex flex-col items-center justify-center gap-1 py-2.5 text-[11px] {{ $isActive ? 'text-[#3E5C4E]' : 'text-[#8A8377]' }}">

                    @switch($item['icon'])
                        @case('home')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>
                            @break
                        @case('pencil')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                            @break
                        @case('briefcase')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            @break
                        @case('user')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="{{ $isActive ? 2.2 : 1.8 }}" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 4-6 8-6s8 2 8 6"/></svg>
                            @break
                    @endswitch

                    <span class="{{ $isActive ? 'font-medium' : '' }}">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

</body>
</html>
