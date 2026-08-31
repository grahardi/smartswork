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
            --ink: #16231F;
            --ink-soft: #223830;
            --paper: #F6F1E7;
            --paper-line: #DAD4C4;
            --moss: #3E5C4E;
            --ochre: #B9832F;
            --text-on-ink: #EDE7D9;
            --text-on-ink-muted: #9CAA9F;
            --text-on-paper: #2A2621;
            --text-on-paper-muted: #6E675A;
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
                            <div style="color:#A32D2D; font-size:12.5px; margin-top:4px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="swk-field">
                        <label for="password">Kata sandi</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <div style="color:#A32D2D; font-size:12.5px; margin-top:4px;">{{ $message }}</div>
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
