<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4F46E5">

    <title>{{ config('app.name', 'SMARTS Work') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#F5F6FD] text-[#1F2333]" style="font-family: 'IBM Plex Sans', system-ui, sans-serif;">

    @auth
        @if (auth()->user()->is_demo)
            <div class="bg-[#F59E0B] text-[#1F2333] text-center text-xs font-medium py-2 px-4">
                Akun demo (read-only) — perubahan tidak disimpan.
            </div>
        @endif
    @endauth

    @if (session('demo_blocked'))
        <div class="bg-[#FEE2E2] text-[#B91C1C] text-center text-xs py-2 px-4">
            {{ session('demo_blocked') }}
        </div>
    @endif

    {{-- Top bar --}}
    <header class="bg-white border-b border-[#E7E9F5]">
        <div class="max-w-lg mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @unless (request()->routeIs('dashboard'))
                    <a href="{{ route('dashboard') }}" class="text-[#4F46E5]" aria-label="Kembali ke beranda">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </a>
                @endunless
                <span class="text-[15px] font-semibold text-[#1F2333]">
                    {{ $header ?? 'SMARTS Work' }}
                </span>
            </div>

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-[#7B7F99]" aria-label="Keluar" title="Keluar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
                    </button>
                </form>
            @endauth
        </div>
    </header>

    {{-- Konten --}}
    <main class="max-w-lg mx-auto pb-10">
        {{ $slot }}
    </main>

</body>
</html>
