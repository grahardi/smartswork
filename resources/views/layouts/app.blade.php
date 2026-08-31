<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#16231F">

    <title>{{ config('app.name', 'SMARTS Work') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#F6F1E7] text-[#2A2621]" style="font-family: 'IBM Plex Sans', system-ui, sans-serif;">

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
    <header class="bg-[#16231F] text-[#EDE7D9]">
        <div class="max-w-lg mx-auto px-4 py-4 flex items-center gap-3">
            @unless (request()->routeIs('dashboard'))
                <a href="{{ route('dashboard') }}" class="text-[#EDE7D9]" aria-label="Kembali ke beranda">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
            @endunless
            <span class="text-[15px] font-medium">
                {{ $header ?? 'SMARTS Work' }}
            </span>
        </div>
    </header>

    {{-- Konten --}}
    <main class="max-w-lg mx-auto pb-10">
        {{ $slot }}
    </main>

</body>
</html>
