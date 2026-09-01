<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMARTS Work — Catatan kerja yang mengalir</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=DM+Sans:wght@500&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #043873;
            --navy-soft: #0A4A8F;
            --blue: #4F9CF9;
            --blue-light: #C4DEFD;
            --yellow: #FFE492;
            --ink: #0F1B2D;
            --muted: #5C6B82;
            --bg: #FFFFFF;
            --bg-alt: #F5F8FD;
            --border: #E4EBF5;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; color: var(--ink); background: var(--bg); }
        p { margin: 0; }
        a { text-decoration: none; color: inherit; }
        .wrap { max-width: 1360px; margin: 0 auto; padding: 0 24px; }

        /* Header */
        header.swk-header {
            background: var(--navy);
            padding: 18px 0;
        }
        .swk-header-inner { display: flex; align-items: center; justify-content: space-between; }
        .swk-logo { font-family: 'Inter', sans-serif; font-weight: 800; font-size: 22px; color: #fff; display: flex; align-items: center; gap: 10px; }
        .swk-logo-mark { width: 30px; height: 30px; border-radius: 8px; background: var(--blue); display: flex; align-items: center; justify-content: center; font-size: 14px; }
        nav.swk-nav { display: flex; align-items: center; gap: 40px; }
        .swk-nav-links { display: flex; gap: 28px; font-family: 'DM Sans', sans-serif; font-weight: 500; font-size: 15px; color: #fff; }
        .swk-nav-btns { display: flex; gap: 14px; }
        .swk-btn-yellow { background: var(--yellow); color: var(--navy); font-weight: 500; padding: 12px 22px; border-radius: 8px; font-size: 14.5px; }
        .swk-btn-blue { background: var(--blue); color: #fff; font-weight: 500; padding: 12px 22px; border-radius: 8px; font-size: 14.5px; display: inline-flex; align-items: center; gap: 8px; }

        /* Hero */
        .swk-hero {
            background: var(--navy);
            padding: 90px 0 110px;
            position: relative;
            overflow: hidden;
        }
        .swk-hero-inner { display: grid; grid-template-columns: 1fr 0.85fr; gap: 60px; align-items: center; position: relative; z-index: 1; }
        .swk-hero h1 { color: #fff; font-weight: 800; font-size: 46px; line-height: 1.15; letter-spacing: -0.5px; margin-bottom: 20px; }
        .swk-hero p.lede { color: rgba(255,255,255,0.75); font-size: 17px; line-height: 1.6; max-width: 460px; margin-bottom: 28px; }
        .swk-hero-log {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px;
            padding: 22px 26px;
        }
        .swk-hero-log-entry { display: grid; grid-template-columns: 70px 1fr; gap: 16px; padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .swk-hero-log-entry:last-child { border-bottom: none; padding-bottom: 0; }
        .swk-hero-log-entry:first-child { padding-top: 0; }
        .swk-hero-log-date { color: rgba(255,255,255,0.5); font-size: 13px; font-weight: 500; }
        .swk-hero-log-body p.title { color: #fff; font-size: 13.5px; margin-bottom: 3px; }
        .swk-hero-log-body span.tag { color: var(--yellow); font-size: 11.5px; }

        /* Sections */
        section.swk-section { padding: 90px 0; }
        section.swk-section.alt { background: var(--bg-alt); }
        .swk-section-head { max-width: 620px; margin: 0 auto 50px; text-align: center; }
        .swk-section-head h2 { font-size: 32px; font-weight: 800; letter-spacing: -0.5px; margin-bottom: 14px; }
        .swk-section-head p { color: var(--muted); font-size: 16px; line-height: 1.6; }

        .swk-feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .swk-feature-card { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 28px; }
        .swk-feature-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
        .swk-feature-card h3 { font-size: 17px; font-weight: 700; margin-bottom: 8px; }
        .swk-feature-card p { color: var(--muted); font-size: 14.5px; line-height: 1.6; }

        .swk-split { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; }
        .swk-split img.placeholder, .swk-split .placeholder {
            background: var(--blue-light); border-radius: 20px; width: 100%; aspect-ratio: 4/3;
        }
        .swk-split h2 { font-size: 30px; font-weight: 800; margin-bottom: 16px; letter-spacing: -0.4px; }
        .swk-split p.desc { color: var(--muted); font-size: 16px; line-height: 1.7; margin-bottom: 24px; }
        .swk-btn-navy { background: var(--navy); color: #fff; font-weight: 500; padding: 14px 26px; border-radius: 8px; font-size: 15px; display: inline-flex; align-items: center; gap: 8px; }

        /* CTA */
        .swk-cta { background: var(--navy); border-radius: 24px; padding: 70px 40px; text-align: center; }
        .swk-cta h2 { color: #fff; font-size: 32px; font-weight: 800; margin-bottom: 14px; }
        .swk-cta p { color: rgba(255,255,255,0.7); font-size: 16px; margin-bottom: 30px; }

        /* Footer */
        footer.swk-footer { background: var(--navy); padding: 60px 0 30px; color: rgba(255,255,255,0.6); }
        .swk-footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .swk-footer-grid h4 { color: #fff; font-size: 14px; font-weight: 700; margin-bottom: 16px; }
        .swk-footer-grid a { display: block; font-size: 14px; margin-bottom: 10px; color: rgba(255,255,255,0.6); }
        .swk-footer-bottom { border-top: 1px solid rgba(255,255,255,0.12); padding-top: 24px; font-size: 13px; display: flex; justify-content: space-between; }

        @media (max-width: 900px) {
            nav.swk-nav .swk-nav-links { display: none; }
            .swk-hero-inner, .swk-split { grid-template-columns: 1fr; }
            .swk-feature-grid { grid-template-columns: 1fr; }
            .swk-footer-grid { grid-template-columns: 1fr 1fr; }
            .swk-hero h1 { font-size: 34px; }
        }
    </style>
</head>
<body>

    <header class="swk-header">
        <div class="wrap swk-header-inner">
            <a href="{{ url('/') }}" class="swk-logo">
                <span class="swk-logo-mark">SW</span>
                SMARTS Work
            </a>
            <nav class="swk-nav">
                <div class="swk-nav-links">
                    <a href="#fitur">Fitur</a>
                    <a href="#kolaborasi">Kolaborasi</a>
                    <a href="#tentang">Tentang</a>
                </div>
                <div class="swk-nav-btns">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="swk-btn-blue">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="swk-btn-yellow">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="swk-btn-blue">Daftar Gratis →</a>
                        @endif
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <section class="swk-hero">
        <div class="wrap swk-hero-inner">
            <div>
                <h1>{{ \App\Models\Setting::get('landing_headline', 'Setiap hari kerjamu, tercatat rapi.') }}</h1>
                <p class="lede">{{ \App\Models\Setting::get('landing_subtext', 'Jurnal harian, keuangan, tempat kerja, dan kolaborasi dengan teman — semua dalam satu tempat. Dari proyek kantor sampai urusan rumah, tanpa ribet pindah-pindah aplikasi.') }}</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="swk-btn-blue">Mulai catat hari ini →</a>
                @endif
            </div>
            <div class="swk-hero-log">
                <div class="swk-hero-log-entry">
                    <div class="swk-hero-log-date">23 Agt</div>
                    <div class="swk-hero-log-body">
                        <p class="title">Antar anak ke sekolah, lalu cek jaringan pelanggan.</p>
                        <span class="tag">Pribadi &amp; Bellanet</span>
                    </div>
                </div>
                <div class="swk-hero-log-entry">
                    <div class="swk-hero-log-date">22 Agt</div>
                    <div class="swk-hero-log-body">
                        <p class="title">Catat pemasukan proyek, saldo bulan ini otomatis update.</p>
                        <span class="tag">Keuangan</span>
                    </div>
                </div>
                <div class="swk-hero-log-entry">
                    <div class="swk-hero-log-date">21 Agt</div>
                    <div class="swk-hero-log-body">
                        <p class="title">Undang teman jadi kolaborator project — kerja bareng.</p>
                        <span class="tag">Kolaborasi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="swk-section" id="fitur">
        <div class="wrap">
            <div class="swk-section-head">
                <h2>Semua yang biasanya tercecer, sekarang satu tempat</h2>
                <p>Enam hal yang paling sering dicari orang tiap hari kerja — sudah kami rapikan jadi satu aplikasi.</p>
            </div>
            <div class="swk-feature-grid">
                <div class="swk-feature-card">
                    <div class="swk-feature-icon" style="background:#EFF6FF;">✍️</div>
                    <h3>Jurnal Harian</h3>
                    <p>Catat aksi harian per proyek lengkap dengan foto dan keterangan, kapan saja sepanjang hari.</p>
                </div>
                <div class="swk-feature-card">
                    <div class="swk-feature-icon" style="background:#FFF7E0;">🏢</div>
                    <h3>Tempat Kerja</h3>
                    <p>Kelola lebih dari satu tempat kerja sekaligus, lengkap dengan project dan titik lokasinya.</p>
                </div>
                <div class="swk-feature-card">
                    <div class="swk-feature-icon" style="background:#E9F9F3;">💰</div>
                    <h3>Keuangan</h3>
                    <p>Pemasukan dan pengeluaran dengan kategori yang bisa kamu atur sendiri, plus riwayat lengkap.</p>
                </div>
                <div class="swk-feature-card">
                    <div class="swk-feature-icon" style="background:#FDEFF7;">🏠</div>
                    <h3>Tempat Tinggal</h3>
                    <p>Simpan beberapa alamat dengan titik koordinat — satu ditandai sebagai tempat tinggal utama.</p>
                </div>
                <div class="swk-feature-card">
                    <div class="swk-feature-icon" style="background:#EFF6FF;">🤝</div>
                    <h3>Teman</h3>
                    <p>Tambah teman, lalu saling lihat aktivitas kerja secara read-only — cukup dengan email.</p>
                </div>
                <div class="swk-feature-card">
                    <div class="swk-feature-icon" style="background:#FFF7E0;">📋</div>
                    <h3>Kolaborasi Project</h3>
                    <p>Undang teman jadi kolaborator di project tertentu supaya bisa kerja bareng (cowork).</p>
                </div>
            </div>
        </div>
    </section>

    <section class="swk-section alt" id="kolaborasi">
        <div class="wrap swk-split">
            <div class="placeholder"></div>
            <div>
                <h2>Kerja bareng teman, bukan cuma catat sendiri</h2>
                <p class="desc">Undang teman yang sudah terhubung untuk jadi kolaborator di project tertentu. Mereka bisa ikut mencatat aksi harian di project itu — cocok untuk kerja lintas tim atau proyek bersama di luar tempat kerja formal.</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="swk-btn-navy">Coba sekarang →</a>
                @endif
            </div>
        </div>
    </section>

    <section class="swk-section" id="tentang">
        <div class="wrap">
            <div class="swk-cta">
                <h2>{{ \App\Models\Setting::get('landing_cta_headline', 'Mulai catat hari kerjamu sekarang') }}</h2>
                <p>{{ \App\Models\Setting::get('landing_cta_subtext', 'Gratis, tanpa perlu kartu kredit. Data diri lengkap dalam hitungan menit.') }}</p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="swk-btn-blue">Daftar Gratis →</a>
                @endif
            </div>
        </div>
    </section>

    <footer class="swk-footer">
        <div class="wrap">
            <div class="swk-footer-grid">
                <div>
                    <div class="swk-logo" style="margin-bottom:14px;">
                        <span class="swk-logo-mark">SW</span>
                        SMARTS Work
                    </div>
                    <p style="font-size:14px; line-height:1.6; max-width:260px;">Satu tempat untuk jurnal, keuangan, tempat kerja, dan kolaborasi kerja sehari-hari.</p>
                </div>
                <div>
                    <h4>Produk</h4>
                    <a href="#fitur">Fitur</a>
                    <a href="#kolaborasi">Kolaborasi</a>
                </div>
                <div>
                    <h4>Akun</h4>
                    <a href="{{ route('login') }}">Masuk</a>
                    @if (Route::has('register'))<a href="{{ route('register') }}">Daftar</a>@endif
                </div>
                <div>
                    <h4>Lainnya</h4>
                    <a href="#tentang">Tentang</a>
                </div>
            </div>
            <div class="swk-footer-bottom">
                <span>SMARTS Work</span>
                <span>© {{ date('Y') }}</span>
            </div>
        </div>
    </footer>

</body>
</html>
