<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SMARTS Work') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    @auth
        @if (auth()->user()->is_demo)
            <div style="background:#B9832F; color:#16231F; text-align:center; padding:8px 16px; font-size:13.5px; font-weight:500;">
                Kamu sedang menjelajah sebagai akun demo (read-only) — perubahan tidak akan disimpan.
            </div>
        @endif
    @endauth

    @if (session('demo_blocked'))
        <div style="background:#FCEBEB; color:#791F1F; text-align:center; padding:8px 16px; font-size:13.5px;">
            {{ session('demo_blocked') }}
        </div>
    @endif

    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
</html>
