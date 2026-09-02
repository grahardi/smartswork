<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar — SMARTS Work</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #4F46E5;
            --ink-soft: #4338CA;
            --paper: #F5F6FD;
            --paper-line: #E5E7F5;
            --moss: #4F46E5;
            --ochre: #F59E0B;
            --text-on-ink: #FFFFFF;
            --text-on-ink-muted: #C7D2FE;
            --text-on-paper: #1F2333;
            --text-on-paper-muted: #7B7F99;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'IBM Plex Sans', sans-serif; color: var(--text-on-paper); background: var(--paper); }
        .swk-wrap { display: grid; grid-template-columns: 1.1fr 1fr; min-height: 100vh; }
        .swk-showcase { background: var(--ink); color: var(--text-on-ink); padding: 4rem 3.5rem; display: flex; flex-direction: column; justify-content: space-between; }
        .swk-brand { font-family: 'Newsreader', serif; font-size: 22px; font-weight: 500; }
        .swk-headline { font-family: 'Newsreader', serif; font-size: 34px; font-weight: 400; line-height: 1.35; max-width: 460px; margin: 2.5rem 0 3rem; }
        .swk-headline em { font-style: italic; }
        .swk-foot-note { font-size: 13px; color: var(--text-on-ink-muted); max-width: 420px; line-height: 1.6; }
        .swk-form-side { background: var(--paper); display: flex; align-items: center; justify-content: center; padding: 3rem; }
        .swk-form-card { width: 100%; max-width: 380px; }
        .swk-form-card h1 { font-family: 'Newsreader', serif; font-weight: 500; font-size: 26px; margin: 0 0 6px; }
        .swk-sub { margin: 0 0 1.75rem; color: var(--text-on-paper-muted); font-size: 14.5px; }
        .swk-field { margin-bottom: 1.1rem; }
        .swk-field label { display: block; font-size: 13px; font-weight: 500; margin-bottom: 6px; }
        .swk-field input { width: 100%; padding: 11px 14px; font-size: 14.5px; font-family: inherit; color: var(--text-on-paper); background: #fff; border: 1px solid var(--paper-line); border-radius: 6px; outline: none; box-sizing: border-box; }
        .swk-field input:focus { border-color: var(--moss); box-shadow: 0 0 0 3px rgba(79,70,229,0.12); }
        .swk-submit { width: 100%; padding: 12px 16px; background: var(--moss); color: #fff; border: none; border-radius: 6px; font-family: inherit; font-size: 14.5px; font-weight: 500; cursor: pointer; }
        .swk-submit:hover { background: var(--ink-soft); }
        .swk-divider { display: flex; align-items: center; gap: 12px; margin: 1.5rem 0; }
        .swk-divider div { flex: 1; height: 1px; background: var(--paper-line); }
        .swk-divider span { font-size: 12px; color: var(--text-on-paper-muted); }
        .swk-google-btn { display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 11px 16px; border: 1px solid var(--paper-line); border-radius: 6px; font-size: 14px; font-weight: 500; color: var(--text-on-paper); background: #fff; text-decoration: none; }
        .swk-login-hint { margin-top: 1.75rem; font-size: 13.5px; color: var(--text-on-paper-muted); text-align: center; }
        .swk-login-hint a { color: var(--moss); font-weight: 500; text-decoration: none; }
        .swk-login-hint a:hover { text-decoration: underline; }
        .swk-error { color: #DC2626; font-size: 12.5px; margin-top: 4px; }
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
                <div class="swk-headline">Mulai catat hari kerjamu, <em>gratis hari ini juga</em>.</div>
            </div>
            <p class="swk-foot-note">Jurnal, keuangan, tempat kerja, dan kolaborasi — satu akun untuk semuanya.</p>
        </div>

        <div class="swk-form-side">
            <div class="swk-form-card">
                <h1>Daftar</h1>
                <p class="swk-sub">Buat akun baru untuk mulai mencatat.</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="swk-field">
                        <label for="name">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @error('name')<div class="swk-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="swk-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">
                        @error('email')<div class="swk-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="swk-field">
                        <label for="password">Kata sandi</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                        @error('password')<div class="swk-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="swk-field">
                        <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                        @error('password_confirmation')<div class="swk-error">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="swk-submit">Daftar</button>
                </form>

                <div class="swk-divider">
                    <div></div><span>atau</span><div></div>
                </div>

                <a href="{{ route('auth.google') }}" class="swk-google-btn">
                    <svg width="18" height="18" viewBox="0 0 18 18"><path fill="#4285F4" d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84c-.21 1.13-.85 2.09-1.8 2.73v2.27h2.92c1.71-1.57 2.68-3.88 2.68-6.64z"/><path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.92-2.27c-.81.54-1.84.86-3.04.86-2.34 0-4.32-1.58-5.03-3.7H.96v2.34C2.44 15.98 5.48 18 9 18z"/><path fill="#FBBC05" d="M3.97 10.71c-.18-.54-.28-1.11-.28-1.71s.1-1.17.28-1.71V4.95H.96C.35 6.17 0 7.55 0 9s.35 2.83.96 4.05l3.01-2.34z"/><path fill="#EA4335" d="M9 3.58c1.32 0 2.51.45 3.44 1.35l2.59-2.59C13.46.89 11.43 0 9 0 5.48 0 2.44 2.02.96 4.95l3.01 2.34C4.68 5.16 6.66 3.58 9 3.58z"/></svg>
                    Daftar dengan Google
                </a>

                <div class="swk-login-hint">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
