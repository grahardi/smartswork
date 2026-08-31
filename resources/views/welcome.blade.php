<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMARTS Work — Catatan kerja yang mengalir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,600;1,6..72,400&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
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
        body {
            margin: 0;
            font-family: 'IBM Plex Sans', sans-serif;
            color: var(--text-on-paper);
            background: var(--paper);
        }
        a { color: inherit; }

        .swk-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.75rem 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .swk-nav-brand {
            font-family: 'Newsreader', serif;
            font-size: 20px;
            font-weight: 500;
        }
        .swk-nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            font-size: 14px;
        }
        .swk-nav-links a.swk-cta {
            background: var(--moss);
            color: var(--paper);
            padding: 9px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
        }
        .swk-nav-links a.swk-plain { text-decoration: none; color: var(--text-on-paper-muted); }
        .swk-nav-links a.swk-plain:hover { color: var(--text-on-paper); }

        .swk-hero {
            background: var(--ink);
            color: var(--text-on-ink);
            padding: 4.5rem 4rem 5rem;
        }
        .swk-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 0.9fr;
            gap: 3rem;
            align-items: center;
        }
        .swk-hero h1 {
            font-family: 'Newsreader', serif;
            font-weight: 400;
            font-size: 44px;
            line-height: 1.3;
            margin: 0 0 1.5rem;
            max-width: 520px;
        }
        .swk-hero h1 em { font-style: italic; }
        .swk-hero p.swk-lede {
            font-size: 16px;
            line-height: 1.7;
            color: var(--text-on-ink-muted);
            max-width: 440px;
            margin: 0 0 2rem;
        }
        .swk-hero-actions { display: flex; gap: 14px; }
        .swk-btn-primary {
            background: var(--ochre);
            color: var(--ink);
            padding: 12px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14.5px;
        }
        .swk-btn-ghost {
            border: 1px solid rgba(237,231,217,0.3);
            color: var(--text-on-ink);
            padding: 12px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14.5px;
        }

        .swk-log {
            background: rgba(237,231,217,0.04);
            border: 1px solid rgba(237,231,217,0.12);
            border-radius: 10px;
            padding: 1.5rem 1.75rem;
        }
        .swk-log-entry {
            display: grid;
            grid-template-columns: 70px 1fr;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(237,231,217,0.12);
        }
        .swk-log-entry:last-child { border-bottom: none; padding-bottom: 0; }
        .swk-log-entry:first-child { padding-top: 0; }
        .swk-log-date { font-family: 'Newsreader', serif; font-size: 14px; color: var(--text-on-ink-muted); padding-top: 2px; }
        .swk-log-body p { margin: 0 0 4px; font-size: 13.5px; line-height: 1.55; }
        .swk-log-tag { font-size: 11.5px; color: var(--ochre); }
        .swk-log-tag::before { content: ''; display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: var(--ochre); margin-right: 6px; }

        .swk-features {
            max-width: 1200px;
            margin: 0 auto;
            padding: 5rem 4rem;
        }
        .swk-features h2 {
            font-family: 'Newsreader', serif;
            font-weight: 400;
            font-size: 28px;
            max-width: 480px;
            line-height: 1.4;
            margin: 0 0 3rem;
        }
        .swk-feature-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }
        .swk-feature {
            border-left: 2px solid var(--moss);
            padding: 0 1.5rem;
        }
        .swk-feature h3 {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 10px;
            color: var(--text-on-paper);
        }
        .swk-feature p {
            font-size: 13.5px;
            line-height: 1.65;
            color: var(--text-on-paper-muted);
            margin: 0;
        }

        .swk-footer {
            border-top: 1px solid var(--paper-line);
            padding: 2rem 4rem;
            max-width: 1200px;
            margin: 0 auto;
            font-size: 13px;
            color: var(--text-on-paper-muted);
            display: flex;
            justify-content: space-between;
        }

        @media (max-width: 900px) {
            .swk-nav, .swk-hero, .swk-features, .swk-footer { padding-left: 1.5rem; padding-right: 1.5rem; }
            .swk-hero-inner { grid-template-columns: 1fr; }
            .swk-feature-grid { grid-template-columns: repeat(2, 1fr); gap: 2rem 1.5rem; }
        }
    </style>
</head>
<body>
    <nav class="swk-nav">
        <div class="swk-nav-brand">SMARTS Work</div>
        <div class="swk-nav-links">
            @auth
                <a href="{{ url('/dashboard') }}" class="swk-cta">Dashboard</a>
            @else
                @if (config('demo.enabled'))
                    <a href="{{ route('demo.login') }}" class="swk-plain">Coba demo</a>
                @endif
                <a href="{{ route('login') }}" class="swk-plain">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="swk-cta">Daftar</a>
                @endif
            @endauth
        </div>
    </nav>

    <section class="swk-hero">
        <div class="swk-hero-inner">
            <div>
                <h1>Setiap hari kerjamu, <em>tercatat rapi</em> — apapun pekerjaannya.</h1>
                <p class="swk-lede">Jurnal harian, jadwal, calendar, dan kolaborasi dalam satu tempat. Dari proyek kantor sampai urusan rumah — semua tercatat, tanpa ribet pindah-pindah aplikasi.</p>
                <div class="swk-hero-actions">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="swk-btn-primary">Mulai catat hari ini</a>
                    @endif
                    @if (config('demo.enabled'))
                        <a href="{{ route('demo.login') }}" class="swk-btn-ghost">Coba demo</a>
                    @endif
                </div>
            </div>
            <div class="swk-log">
                <div class="swk-log-entry">
                    <div class="swk-log-date">23 Agt</div>
                    <div class="swk-log-body">
                        <p>Antar anak ke sekolah, lalu cek jaringan pelanggan area Turen.</p>
                        <span class="swk-log-tag">Pribadi &amp; Bellanet</span>
                    </div>
                </div>
                <div class="swk-log-entry">
                    <div class="swk-log-date">22 Agt</div>
                    <div class="swk-log-body">
                        <p>Rilis modul jurnal harian, migrasi database ke MySQL.</p>
                        <span class="swk-log-tag">SMARTS Work</span>
                    </div>
                </div>
                <div class="swk-log-entry">
                    <div class="swk-log-date">21 Agt</div>
                    <div class="swk-log-body">
                        <p>Rapat evaluasi triwulan dengan tim IT sekolah.</p>
                        <span class="swk-log-tag">SMP Negeri 1 Turen</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="swk-features">
        <h2>Empat hal yang biasanya tercecer di aplikasi berbeda — sekarang satu tempat.</h2>
        <div class="swk-feature-grid">
            <div class="swk-feature">
                <h3>Jurnal harian</h3>
                <p>Catat aksi harian per proyek lengkap dengan foto dan keterangan, kapan saja sepanjang hari.</p>
            </div>
            <div class="swk-feature">
                <h3>Jadwal kerja</h3>
                <p>Atur shift dan jadwal, personal maupun tim, tanpa bentrok dengan agenda lain.</p>
            </div>
            <div class="swk-feature">
                <h3>Calendar</h3>
                <p>Lihat semua janji, deadline, dan cuti dalam satu tampilan bersama jadwal kerja.</p>
            </div>
            <div class="swk-feature">
                <h3>Kolaborasi</h3>
                <p>Simpan dan bagikan dokumen per tim dengan izin akses yang jelas, seperti ruang kerja bersama.</p>
            </div>
        </div>
    </section>

    <footer class="swk-footer">
        <span>SMARTS Work</span>
        <span>{{ date('Y') }}</span>
    </footer>
</body>
</html>
