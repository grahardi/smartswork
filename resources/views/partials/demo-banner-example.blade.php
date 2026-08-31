{{-- Tambahkan di bagian atas layout dashboard (mis. resources/views/layouts/app.blade.php),
     tepat di dalam <body>, sebelum konten utama --}}

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
