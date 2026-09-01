<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — SMARTS Work</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #2563EB;
            --ink-soft: #4338CA;
            --paper: #F5F6FD;
            --paper-line: #E5E7F5;
            --moss: #2563EB;
            --ochre: #F59E0B;
            --text-on-ink: #FFFFFF;
            --text-on-ink-muted: #BFDBFE;
            --text-on-paper: #1F2333;
            --text-on-paper-muted: #7B7F99;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'IBM Plex Sans', sans-serif;
            color: var(--text-on-paper);
        }
        .swk-wrap {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            min-height: 100vh;
        }
        .swk-showcase {
            background: var(--ink);
            color: var(--text-on-ink);
            padding: 4rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .swk-brand {
            font-family: 'Newsreader', serif;
            font-size: 22px;
            font-weight: 500;
            letter-spacing: 0.01em;
        }
        .swk-headline {
            font-family: 'Newsreader', serif;
            font-size: 34px;
            font-weight: 400;
            line-height: 1.35;
            max-width: 460px;
            margin: 2.5rem 0 3rem;
        }
        .swk-headline em {
            font-style: italic;
            color: var(--text-on-ink);
        }
        .swk-log {
            display: flex;
            flex-direction: column;
            gap: 0;
            border-top: 1px solid rgba(237,231,217,0.15);
        }
        .swk-log-entry {
            display: grid;
            grid-template-columns: 96px 1fr;
            gap: 20px;
            padding: 18px 0;
            border-bottom: 1px solid rgba(237,231,217,0.15);
        }
        .swk-log-date {
            font-family: 'Newsreader', serif;
            font-size: 15px;
            color: var(--text-on-ink-muted);
            padding-top: 2px;
        }
        .swk-log-body p {
            margin: 0 0 4px;
            font-size: 14.5px;
            line-height: 1.5;
        }
        .swk-log-tag {
            display: inline-block;
            font-size: 12px;
            color: var(--ochre);
        }
        .swk-log-tag::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--ochre);
            margin-right: 6px;
        }
        .swk-foot-note {
            font-size: 13px;
            color: var(--text-on-ink-muted);
            max-width: 420px;
            line-height: 1.6;
        }
        .swk-form-side {
            background: var(--paper);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }
        .swk-form-card {
            width: 100%;
            max-width: 380px;
        }
        .swk-form-card h1 {
            font-family: 'Newsreader', serif;
            font-weight: 500;
            font-size: 26px;
            margin: 0 0 6px;
            color: var(--text-on-paper);
        }
        .swk-form-card p.swk-sub {
            margin: 0 0 2.25rem;
            color: var(--text-on-paper-muted);
            font-size: 14.5px;
        }
        .swk-field { margin-bottom: 1.25rem; }
        .swk-field label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-on-paper);
            margin-bottom: 6px;
        }
        .swk-field input {
            width: 100%;
            padding: 11px 14px;
            font-size: 14.5px;
            font-family: inherit;
            color: var(--text-on-paper);
            background: #fff;
            border: 1px solid var(--paper-line);
            border-radius: 6px;
            outline: none;
        }
        .swk-field input:focus {
            border-color: var(--moss);
            box-shadow: 0 0 0 3px rgba(62,92,78,0.12);
        }
        .swk-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            font-size: 13.5px;
        }
        .swk-row label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-on-paper-muted);
        }
        .swk-row a {
            color: var(--moss);
            text-decoration: none;
        }
        .swk-row a:hover { text-decoration: underline; }
        .swk-submit {
            width: 100%;
            padding: 12px 16px;
            background: var(--moss);
            color: var(--paper);
            border: none;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14.5px;
            font-weight: 500;
            cursor: pointer;
        }
        .swk-submit:hover { background: var(--ink-soft); }
        .swk-register-hint {
            margin-top: 2rem;
            font-size: 13.5px;
            color: var(--text-on-paper-muted);
            text-align: center;
        }
        .swk-register-hint a {
            color: var(--moss);
            font-weight: 500;
            text-decoration: none;
        }
        .swk-register-hint a:hover { text-decoration: underline; }
        @media (max-width: 860px) {
            .swk-wrap { grid-template-columns: 1fr; }
            .swk-showcase { display: none; }
            .swk-form-side { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="swk-wrap">
        <div class="swk-showcase">
            <div>
                <div class="swk-brand">SMARTS Work</div>
                <div class="swk-headline">Setiap hari kerjamu, <em>tercatat rapi</em> — dari proyek kantor sampai urusan rumah.</div>
                <div class="swk-log">
                    <div class="swk-log-entry">
                        <div class="swk-log-date">23 Agt</div>
                        <div class="swk-log-body">
                            <p>Antar anak ke sekolah, lalu lanjut cek jaringan pelanggan area Turen.</p>
                            <span class="swk-log-tag">Pribadi &amp; Bellanet</span>
                        </div>
                    </div>
                    <div class="swk-log-entry">
                        <div class="swk-log-date">22 Agt</div>
                        <div class="swk-log-body">
                            <p>Rilis modul jurnal harian untuk SMARTS Work, migrasi ke MySQL.</p>
                            <span class="swk-log-tag">SMARTS Work</span>
                        </div>
                    </div>
                    <div class="swk-log-entry">
                        <div class="swk-log-date">21 Agt</div>
                        <div class="swk-log-body">
                            <p>Rapat evaluasi triwulan dengan tim IT SMP Negeri 1 Turen.</p>
                            <span class="swk-log-tag">SMP Negeri 1 Turen</span>
                        </div>
                    </div>
                </div>
            </div>
            <p class="swk-foot-note">Satu tempat untuk jurnal, jadwal, dan proyek — apapun pekerjaannya, apapun tempatnya.</p>
        </div>

        <div class="swk-form-side">
            <div class="swk-form-card">
                <h1>Masuk</h1>
                <p class="swk-sub">Lanjutkan catatan kerjamu hari ini.</p>

                @if (session('status'))
                    <div style="margin-bottom: 1.25rem; font-size: 13.5px; color: var(--moss);">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="swk-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
                        @error('email')
                            <div style="color:#DC2626; font-size:12.5px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="swk-field">
                        <label for="password">Kata sandi</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <div style="color:#DC2626; font-size:12.5px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="swk-row">
                        <label>
                            <input type="checkbox" name="remember">
                            Ingat saya
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                        @endif
                    </div>

                    <button type="submit" class="swk-submit">Masuk</button>
                </form>

                <div style="display:flex; align-items:center; gap:12px; margin:1.5rem 0;">
                    <div style="flex:1; height:1px; background:var(--paper-line);"></div>
                    <span style="font-size:12px; color:var(--text-on-paper-muted);">atau</span>
                    <div style="flex:1; height:1px; background:var(--paper-line);"></div>
                </div>

                <a href="{{ route('auth.google') }}" style="display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:11px 16px; border:1px solid var(--paper-line); border-radius:6px; font-size:14px; font-weight:500; color:var(--text-on-paper); background:#fff;">
                    <svg width="18" height="18" viewBox="0 0 18 18"><path fill="#4285F4" d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84c-.21 1.13-.85 2.09-1.8 2.73v2.27h2.92c1.71-1.57 2.68-3.88 2.68-6.64z"/><path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.92-2.27c-.81.54-1.84.86-3.04.86-2.34 0-4.32-1.58-5.03-3.7H.96v2.34C2.44 15.98 5.48 18 9 18z"/><path fill="#FBBC05" d="M3.97 10.71c-.18-.54-.28-1.11-.28-1.71s.1-1.17.28-1.71V4.95H.96C.35 6.17 0 7.55 0 9s.35 2.83.96 4.05l3.01-2.34z"/><path fill="#EA4335" d="M9 3.58c1.32 0 2.51.45 3.44 1.35l2.59-2.59C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.95l3.01 2.34C4.68 5.16 6.66 3.58 9 3.58z"/></svg>
                    Masuk dengan Google
                </a>

                @if (Route::has('register'))
                    <div class="swk-register-hint">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
