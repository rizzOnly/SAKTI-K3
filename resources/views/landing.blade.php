<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKTI K3- Sistem Aplikasi K3 Terintegrasi</title>
    <link rel="icon" href="{{ asset('images/logo-sakti.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <style>
        /* ── Sembunyikan Scrollbar Bawaan Browser ── */

        /* Untuk Chrome, Safari, Edge, dan Opera */
        html::-webkit-scrollbar {
            display: none;
        }

        /* Untuk Firefox dan Internet Explorer lama */
        html {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;     /* Firefox */
        }

        :root { --pln-blue: #003D7C; --pln-yellow: #FFC72C; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; background: #f8fafc; }

        /* ── Navbar ── */
        .navbar { background: var(--pln-blue); position: sticky; top: 0; z-index: 50; box-shadow: 0 2px 20px rgba(0,0,0,.25); }
        .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 1rem; display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .nav-logo  { width: 40px; height: 40px; background: var(--pln-yellow); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; color: var(--pln-blue); flex-shrink: 0; }
        .nav-title { color: #fff; font-weight: 700; font-size: 15px; line-height: 1.2; }
        .nav-sub   { color: #93c5fd; font-size: 11px; }
        .nav-links { display: flex; gap: 24px; }
        .nav-links a { color: #bfdbfe; font-size: 14px; text-decoration: none; transition: color .2s; }
        .nav-links a:hover { color: #fff; }
        .nav-actions { display: flex; gap: 8px; }
        .btn-nav-yellow { background: var(--pln-yellow); color: var(--pln-blue); padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: background .2s; white-space: nowrap; }
        .btn-nav-yellow:hover { background: #ffd855; }
        .btn-nav-teal { background: #0d9488; color: #fff; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; transition: background .2s; white-space: nowrap; }
        .btn-nav-teal:hover { background: #0f766e; }
        .mobile-menu { display: none; background: #002d5c; }
        .mobile-menu.open { display: block; }
        .mobile-menu a { display: block; color: #bfdbfe; padding: 12px 20px; font-size: 15px; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,.06); }
        .mobile-menu a:hover { background: rgba(255,255,255,.05); color: #fff; }
        .mobile-actions { padding: 12px 16px; display: flex; gap: 8px; }

        /* ── Mobile Drawer ── */
        .drawer-overlay {display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5); z-index: 100; backdrop-filter: blur(2px);}
        .drawer-overlay.open { display: block; }
        .drawer {position: fixed; top: 0; left: 0; height: 100%; width: 280px; background: #001f4d; z-index: 101; transform: translateX(-100%); transition: transform .3s cubic-bezier(.4,0,.2,1); display: flex; flex-direction: column; overflow-y: auto; }
        .drawer.open { transform: translateX(0); }
        .drawer-header { background: #003D7C; padding: 20px 16px; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid rgba(255,255,255,.08); flex-shrink: 0; }
        .drawer-logo { width: 38px; height: 38px; background: #FFC72C; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 12px; color: #003D7C; flex-shrink: 0; }
        .drawer-brand-title { color: #fff; font-weight: 700; font-size: 14px; }
        .drawer-brand-sub   { color: #93c5fd; font-size: 10px; margin-top: 2px; }
        .drawer-nav { padding: 12px 0; flex: 1; }
        .drawer-nav a { display: flex; align-items: center; gap: 12px; color: #bfdbfe; padding: 13px 20px; font-size: 14px; font-weight: 500; text-decoration: none; transition: background .15s, color .15s; border-left: 3px solid transparent; }
        .drawer-nav a:hover { background: rgba(255,255,255,.06); color: #fff; border-left-color: #FFC72C; }
        .drawer-nav .drawer-nav-icon { width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,.08); display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
        .drawer-divider { height: 1px; background: rgba(255,255,255,.07); margin: 8px 16px; }
        .drawer-actions { padding: 16px; display: flex; flex-direction: column; gap: 10px; flex-shrink: 0; border-top: 1px solid rgba(255,255,255,.07); }
        .drawer-actions a {display: flex;align-items: center;justify-content: center;gap: 8px;padding: 12px;border-radius: 12px;font-weight: 700;font-size: 14px;text-decoration: none;transition: opacity .15s, transform .15s; }
        .drawer-actions a:hover { opacity: .9; transform: translateY(-1px); }
        .drawer-btn-yellow { background: #FFC72C; color: #003D7C; }
        .drawer-btn-teal   { background: #0d9488; color: #fff; }
        .drawer-btn-orange { background: #d97706; color: #fff; }

        /* Hamburger button */
        .nav-hamburger { display: none; background: rgba(255,255,255,.1); border: none; cursor: pointer; padding: 8px; border-radius: 8px; transition: background .2s; }
        .nav-hamburger:hover { background: rgba(255,255,255,.2); }

        @media (max-width: 900px) {
            .nav-hamburger { display: flex; align-items: center; justify-content: center; }
            #desktop-nav   { display: none !important; }
        }

        /* ── Banner Swiper ── */
        .banner-swiper { width: 100%; }
        .banner-slide { position: relative; height: 480px; background: #1e3a5f; overflow: hidden; }
        @media (max-width: 768px) { .banner-slide { height: 260px; } }
        .banner-slide img { width: 100%; height: 100%; object-fit: cover; }
        .banner-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.55) 0%, transparent 60%); }
        .banner-caption { position: absolute; bottom: 0; left: 0; right: 0; padding: 2rem; }
        .banner-caption h2 { color: #fff; font-size: clamp(1.2rem, 3vw, 1.8rem); font-weight: 700; margin: 0; text-shadow: 0 1px 6px rgba(0,0,0,.4); }

        /* Hero fallback */
        .hero-fallback { background: linear-gradient(135deg, #003D7C 0%, #1a5ca8 100%); padding: 80px 20px; text-align: center; }
        .hero-fallback h1 { color: #fff; font-size: clamp(1.8rem, 5vw, 3rem); font-weight: 800; margin: 0 0 12px; }
        .hero-fallback p { color: #93c5fd; font-size: clamp(1rem, 2vw, 1.2rem); margin: 0; }

        /* ── Quick Access (Aksi Cepat Pegawai) ── */
        .quick-access { background: var(--pln-blue); padding: 16px; border-bottom: 1px solid rgba(255,255,255,.05); }
        .quick-access-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: center; }
        .quick-label { color: #93c5fd; font-size: 13px; font-weight: 600; white-space: nowrap; margin-right: 4px; }
        .quick-btn { display: flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 700; text-decoration: none; transition: all .2s; white-space: nowrap; }
        .quick-btn-yellow { background: var(--pln-yellow); color: var(--pln-blue); }
        .quick-btn-yellow:hover { background: #ffd855; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(255,199,44,.25); }
        .quick-btn-teal { background: #0d9488; color: #fff; }
        .quick-btn-teal:hover { background: #0f766e; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(13,148,136,.25); }
        .quick-btn-orange { background: #d97706; color: #fff; }
        .quick-btn-orange:hover { background: #b45309; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(217,119,6,.25); }

        /* ── Stats Bar ── */
        .stats-bar { background: #002d5c; padding: 20px 16px; }
        .stats-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .stat-box { background: rgba(255,255,255,.07); border-radius: 12px; padding: 16px; text-align: center; }
        .stat-number { color: var(--pln-yellow); font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-label  { color: #93c5fd; font-size: 12px; margin-top: 4px; }
        @media (max-width: 480px) { .stat-number { font-size: 1.5rem; } .stat-label { font-size: 11px; } }

        /* ── Section Common ── */
        .section { padding: 32px 16px; }
        .section-alt { background: #f1f5f9; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-header { display: flex; align-items: center; gap: 16px; margin-bottom: 36px; }
        .section-title { font-size: clamp(1.3rem, 3vw, 1.75rem); font-weight: 800; color: #111827; white-space: nowrap; }
        .section-line { flex: 1; height: 3px; background: var(--pln-yellow); border-radius: 2px; }

        /* ── YouTube Video ── */
        .video-section { background: linear-gradient(135deg, #001f3f 0%, #003D7C 50%, #00294f 100%); padding: 72px 16px; position: relative; overflow: hidden; }
        .video-section::before { content: ''; position: absolute; top: -60px; right: -60px; width: 300px; height: 300px; background: rgba(255,199,44,.06); border-radius: 50%; }
        .video-section::after  { content: ''; position: absolute; bottom: -80px; left: -40px; width: 250px; height: 250px; background: rgba(255,199,44,.04); border-radius: 50%; }
        .video-inner { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; position: relative; z-index: 1; }
        @media (max-width: 768px) { .video-inner { grid-template-columns: 1fr; gap: 28px; } }
        .video-text { color: #fff; }
        .video-badge { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,199,44,.15); border: 1px solid rgba(255,199,44,.3); color: var(--pln-yellow); padding: 5px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; margin-bottom: 16px; }
        .video-title { font-size: clamp(1.5rem, 3.5vw, 2.2rem); font-weight: 800; line-height: 1.2; margin: 0 0 16px; }
        .video-desc { color: #93c5fd; font-size: 15px; line-height: 1.7; margin: 0 0 24px; }
        .video-cta { display: inline-flex; align-items: center; gap: 8px; background: var(--pln-yellow); color: var(--pln-blue); padding: 12px 22px; border-radius: 12px; font-weight: 700; font-size: 14px; text-decoration: none; transition: all .2s; }
        .video-cta:hover { background: #ffd855; transform: translateY(-2px); }
        .video-player-wrap { position: relative; }
        .video-frame-outer { border-radius: 20px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,.4); border: 3px solid rgba(255,199,44,.2); position: relative; }
        .video-ratio { position: relative; padding-bottom: 56.25%; background: #000; }
        .video-ratio iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: none; }
        .video-playlist-hint { color: #64748b; font-size: 12px; text-align: center; margin-top: 12px; }

        /* ── Artikel Grid ── */
        .articles-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        @media (max-width: 900px) { .articles-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .articles-grid { grid-template-columns: 1fr; } }
        .article-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.06); transition: all .25s; text-decoration: none; display: flex; flex-direction: column; }
        .article-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,.12); transform: translateY(-3px); }
        .article-img { width: 100%; height: 180px; object-fit: cover; transition: transform .3s; }
        .article-card:hover .article-img { transform: scale(1.04); }
        .article-img-wrap { overflow: hidden; height: 180px; }
        .article-img-placeholder { width: 100%; height: 180px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); display: flex; align-items: center; justify-content: center; }
        .article-body { padding: 16px; flex: 1; }
        .article-cat { display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; margin-bottom: 8px; text-transform: capitalize; }
        .cat-kampanye { background: #fee2e2; color: #991b1b; }
        .cat-berita   { background: #dbeafe; color: #1e40af; }
        .cat-panduan  { background: #dcfce7; color: #166534; }
        .cat-lainnya  { background: #fef3c7; color: #92400e; }
        .article-title { font-weight: 700; color: #111827; font-size: 15px; line-height: 1.45; margin: 0 0 8px; }
        .article-date  { color: #9ca3af; font-size: 12px; }

        /* ── Vendor Grid ── */
        .vendors-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 900px) { .vendors-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .vendors-grid { grid-template-columns: 1fr; } }
        .vendor-card { background: #fff; border-radius: 16px; padding: 18px; box-shadow: 0 1px 4px rgba(0,0,0,.06); transition: box-shadow .2s; }
        .vendor-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.1); }
        .vendor-icon { width: 40px; height: 40px; background: #dbeafe; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .vendor-name { font-weight: 700; color: #111827; font-size: 14px; }
        .vendor-bidang { color: #6b7280; font-size: 12px; }
        .vendor-contact { display: flex; align-items: center; gap: 5px; color: #6b7280; font-size: 12px; margin-top: 6px; }

        /* ── Patrol Grid ── */
        .patrol-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        @media (max-width: 900px) { .patrol-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 560px) { .patrol-grid { grid-template-columns: 1fr; } }
        .patrol-card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
        .patrol-img  { width: 100%; height: 160px; object-fit: cover; }
        .patrol-img-placeholder { height: 160px; background: linear-gradient(135deg, #e0f2fe, #bae6fd); display: flex; align-items: center; justify-content: center; }
        .patrol-body { padding: 14px; }
        .patrol-name { font-weight: 600; color: #111827; font-size: 14px; }
        .patrol-date { color: #9ca3af; font-size: 12px; margin-top: 2px; }
        .patrol-note { color: #6b7280; font-size: 13px; margin-top: 6px; }

        /* ── Footer ── */
        .footer { background: var(--pln-blue); color: #fff; padding: 48px 16px 24px; }
        .footer-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; }
        @media (max-width: 700px) { .footer-inner { grid-template-columns: 1fr; gap: 24px; } }
        .footer-heading { font-weight: 700; font-size: 15px; margin-bottom: 12px; }
        .footer-text { color: #93c5fd; font-size: 13px; line-height: 1.7; }
        .footer-link { display: block; color: #93c5fd; font-size: 13px; text-decoration: none; margin-bottom: 8px; transition: color .2s; }
        .footer-link:hover { color: var(--pln-yellow); }
        .footer-bottom { max-width: 1200px; margin: 32px auto 0; padding-top: 20px; border-top: 1px solid rgba(255,255,255,.1); text-align: center; color: #4b7ab5; font-size: 12px; }

        /* ── Swiper custom ── */
        .swiper-button-next, .swiper-button-prev { color: #fff !important; background: rgba(0,0,0,.3); border-radius: 50%; width: 40px !important; height: 40px !important; }
        .swiper-button-next::after, .swiper-button-prev::after { font-size: 16px !important; }
        @media (max-width: 480px) { .swiper-button-next, .swiper-button-prev { display: none !important; } }

        /* ── Patrol iZAT (Redesign Jadwal) ── */
        .patrol-izat-wrap { max-width: 820px; margin: 0 auto; }
        .patrol-card-main { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,.08); border:1px solid #e5e7eb; }
        .patrol-card-header { background:#003D7C; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .patrol-header-left { display:flex; align-items:center; gap:10px; }
        .patrol-icon { width:38px; height:38px; background:rgba(255,255,255,.15); border-radius:9px; display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0; }
        .patrol-title { color:#fff; font-weight:700; font-size:14px; line-height:1.2; }
        .patrol-subtitle { color:#93c5fd; font-size:11px; margin-top:2px; }
        .patrol-count-badge { background:rgba(255,255,255,.12); border-radius:10px; padding:6px 14px; text-align:center; flex-shrink:0; }
        .patrol-count-num { color:#FFC72C; font-size:18px; font-weight:800; display:block; line-height:1; }
        .patrol-count-label { color:#bfdbfe; font-size:10px; display:block; margin-top:2px; }
        .patrol-week-bar { background:#1aad57; padding:6px 16px; display:flex; align-items:center; justify-content:space-between; }
        .patrol-week-label { color:#fff; font-size:11px; font-weight:600; }
        .patrol-week-range { color:rgba(255,255,255,.8); font-size:11px; }
        .patrol-table-wrap { overflow-x:auto; }
        .patrol-table { width:100%; border-collapse:collapse; }
        .patrol-table thead tr { background:#25d366; }
        .patrol-table thead th { padding:9px 12px; font-size:11px; font-weight:700; color:#fff; text-align:left; border-right:1px solid rgba(255,255,255,.15); white-space:nowrap; }
        .patrol-table thead th.col-no { width:40px; text-align:center; }
        .patrol-table thead th.col-status { width:120px; text-align:center; }
        .patrol-table thead th.col-hari { width:90px; }
        .patrol-table thead th.col-unit { width:90px; }
        .patrol-table .row-even { background:#fff; }
        .patrol-table .row-odd  { background:#f0fdf4; }
        .patrol-table tbody td { padding:8px 12px; font-size:12px; border-bottom:1px solid #f3f4f6; border-right:1px solid #f9fafb; }
        .patrol-table tbody td.col-no { text-align:center; color:#9ca3af; font-size:11px; font-weight:600; }
        .patrol-table tbody td.col-nama { font-weight:700; color:#111827; font-size:13px; letter-spacing:.2px; }
        .patrol-table tbody td.col-hari { white-space:nowrap; }
        .patrol-table tbody td.col-status { text-align:center; }
        .patrol-table tbody tr:last-child td { border-bottom:none; }
        .hari-label { font-weight:600; color:#374151; font-size:12px; display:block; }
        .tgl-label  { color:#9ca3af; font-size:11px; }
        .unit-badge { background:#eff6ff; color:#1e40af; font-size:10px; font-weight:600; padding:2px 7px; border-radius:999px; white-space:nowrap; }
        .chip-done    { display:inline-flex; align-items:center; gap:4px; background:#dcfce7; color:#166534; font-size:11px; font-weight:600; padding:3px 8px; border-radius:999px; }
        .chip-pending { display:inline-flex; align-items:center; gap:4px; background:#fef9c3; color:#854d0e; font-size:11px; font-weight:600; padding:3px 8px; border-radius:999px; }
        .patrol-progress { padding:10px 16px; background:#f9fafb; border-top:1px solid #f3f4f6; display:flex; align-items:center; gap:10px; }
        .patrol-prog-label { font-size:11px; color:#6b7280; white-space:nowrap; }
        .patrol-prog-wrap { flex:1; height:5px; background:#e5e7eb; border-radius:3px; overflow:hidden; }
        .patrol-prog-bar { height:100%; background:#16a34a; border-radius:3px; transition:width .4s ease; }
        .patrol-prog-pct { font-size:11px; white-space:nowrap; font-weight:600; }
        .text-green { color:#16a34a; }
        .text-gray  { color:#6b7280; }
        .patrol-footer { background:#f9fafb; border-top:1px solid #f3f4f6; padding:12px 16px; }
        .patrol-salam { font-size:12px; font-weight:600; color:#374151; }
        .patrol-info  { font-size:11px; color:#9ca3af; margin-top:3px; }

        /* ── Grid Layout 2 Kolom (Patrol & Temuan) ── */
        .patrol-temuan-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; max-width: 1200px; margin: 0 auto; }
        @media (max-width: 900px) { .patrol-temuan-grid { grid-template-columns: 1fr; } }

        /* ── Temuan Open CSS ── */
        .temuan-card { background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 2px 16px rgba(0,0,0,.08); border:1px solid #e5e7eb; }
        .temuan-header { background:#003D7C; padding:14px 18px; display:flex; align-items:center; gap:10px; }
        .temuan-icon { width:38px; height:38px; background:rgba(255,255,255,.15); border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .temuan-title { color:#fff; font-size:14px; font-weight:700; }
        .temuan-sub { color:#93c5fd; font-size:11px; margin-top:2px; }
        .temuan-table { width:100%; border-collapse:collapse; }
        .temuan-table thead tr { background:#dc2626; }
        .temuan-table thead th { color:#fff; font-size:11px; font-weight:700; padding:9px 14px; text-align:left; border-right:1px solid rgba(255,255,255,.15); }
        .temuan-table thead th.no  { width:50px; text-align:center; }
        .temuan-table thead th.jml { width:100px; text-align:center; }
        .temuan-table .r-even { background:#fff; }
        .temuan-table .r-odd  { background:#fef2f2; }
        .temuan-table tbody td { padding:9px 14px; font-size:13px; border-bottom:1px solid #f3f4f6; }
        .temuan-table tbody td.no { text-align:center; color:#9ca3af; font-size:11px; font-weight:600; }
        .temuan-table tbody td.bidang { font-weight:600; color:#111827; }
        .temuan-table tbody td.jml { text-align:center; }
        .temuan-table tbody tr:last-child td { border-bottom:none; }
        .jml-badge { display:inline-block; padding:3px 10px; border-radius:999px; font-size:12px; font-weight:700; }
        .jml-badge.danger { background:#fee2e2; color:#991b1b; }
        .jml-badge.warn   { background:#fef3c7; color:#92400e; }
        .jml-badge.ok     { background:#dcfce7; color:#166534; }
        .jml-badge.total  { background:#003D7C; color:#fff; }
    </style>
</head>
<body>

    {{-- ═══════════ NAVBAR ═══════════ --}}
    <header>
        <nav class="navbar">
            <div class="nav-inner">
                <a href="/" class="nav-brand">
                    <img src="{{ asset('images/logo-sakti.png') }}" alt="Logo SAKTI K3" style="height: 44px; width: auto; object-fit: contain;">
                    <div>
                        <div class="nav-title">SAKTI K3</div>
                        <div class="nav-sub">Sistem Keamanan Kerja yang Andal</div>
                    </div>
                </a>

                {{-- Desktop nav --}}
                <div class="nav-links" id="desktop-nav">
                    <a href="#beranda">Beranda</a>
                    <a href="#video">Induction</a>
                    <a href="#artikel">Artikel</a>
                    <a href="#vendor">Vendor</a>
                    <a href="#patrol">Patrol</a>
                </div>

                {{-- Hamburger — hanya muncul di mobile --}}
                <button class="nav-hamburger" id="hamburger" onclick="openDrawer()" aria-label="Buka Menu">
                    <svg width="22" height="22" fill="none" stroke="#fff" stroke-width="2.5"
                         stroke-linecap="round" viewBox="0 0 24 24">
                        <line x1="3" y1="6"  x2="21" y2="6"/>
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Overlay gelap di belakang drawer --}}
        <div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

        {{-- Drawer slide dari kiri --}}
        <div class="drawer" id="drawer">

            {{-- Header drawer --}}
            <div class="drawer-header">
                <img src="{{ asset('images/logo-sakti.png') }}" alt="Logo SAKTI K3" style="height: 44px; width: auto; object-fit: contain;">
                <div>
                    <div class="drawer-brand-title">SAKTI K3</div>
                    <div class="drawer-brand-sub">Sistem Keamanan Kerja yang Andal</div>
                </div>
                {{-- Tombol close --}}
                <button onclick="closeDrawer()"
                        style="margin-left:auto;background:rgba(255,255,255,.1);border:none;cursor:pointer;
                               padding:6px;border-radius:8px;display:flex;align-items:center;justify-content:center">
                    <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5"
                         stroke-linecap="round" viewBox="0 0 24 24">
                        <line x1="18" y1="6"  x2="6"  y2="18"/>
                        <line x1="6"  y1="6"  x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Nav links --}}
            <nav class="drawer-nav">
                <a href="#beranda" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">🏠</span> Beranda
                </a>
                <a href="#video" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">▶️</span> Induction K3
                </a>
                <a href="#artikel" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">📰</span> Artikel K3
                </a>
                <a href="#vendor" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">🏢</span> Vendor
                </a>
                <a href="#patrol" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">👁</span> Patrol iZAT
                </a>
            </nav>

            <div class="drawer-divider"></div>

            {{-- Action buttons di bawah --}}
            <div class="drawer-actions">
                <a href="/pegawai/apd" class="drawer-btn-yellow">
                    🦺 Ambil / Pinjam APD
                </a>
                <a href="/pegawai/booking-klinik" class="drawer-btn-teal">
                    🏥 Booking Klinik
                </a>
                <a href="/vendor/registrasi" class="drawer-btn-orange">
                    🔐 Registrasi Gate Access
                </a>
            </div>

        </div>
    </header>

    {{-- ═══════════ BANNER / HERO ═══════════ --}}
    <section id="beranda">
        @if($banners->isNotEmpty())
        <div class="swiper banner-swiper">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                <div class="swiper-slide banner-slide">
                    <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title ?? 'Banner K3' }}" loading="eager">
                    <div class="banner-overlay"></div>
                    @if($banner->title)
                    <div class="banner-caption">
                        <h2>{{ $banner->title }}</h2>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        @else
        <div class="hero-fallback">
            <h1>Sistem Informasi K3</h1>
            <p>PT PLN Nusantara Power – Unit Pembangkitan Sengkang</p>
        </div>
        @endif
    </section>

    {{-- ═══════════ AKSI CEPAT PEGAWAI ═══════════ --}}
    <div class="quick-access">
        <div class="quick-access-inner">
            <span class="quick-label">Akses Cepat:</span>

            <a href="/pegawai/apd" class="quick-btn quick-btn-yellow">
                <span class="text-base leading-none">🦺</span> Ambil / Pinjam APD
            </a>

            <a href="/pegawai/booking-klinik" class="quick-btn quick-btn-teal">
                <span class="text-base leading-none">🏥</span> Booking Klinik
            </a>

            <a href="/vendor/registrasi" class="quick-btn quick-btn-orange">
                <span class="text-base leading-none">🏢</span> Registrasi Gate Access
            </a>
        </div>
    </div>

    {{-- ═══════════ STATS BAR ═══════════ --}}
    <div class="stats-bar">
        <div class="stats-inner">
            <div class="stat-box">
                <div class="stat-number">{{ \App\Models\ApdItem::count() }}</div>
                <div class="stat-label">Jenis APD</div>
            </div>
            <div class="stat-box">
                <div class="stat-number">{{ \App\Models\CmsArticle::where('is_published', true)->count() }}</div>
                <div class="stat-label">Artikel K3</div>
            </div>
            <div class="stat-box">
                {{-- PERBAIKAN 2: Menghitung total vendor gabungan --}}
                @php
                    $totalVendorWpo = \App\Models\CmsVendor::where('is_active', true)->count();
                    $totalVendorGate = \App\Models\VendorRegistrasi::aktifDanBerlaku()->count();
                    $totalSemuaVendor = $totalVendorWpo + $totalVendorGate;
                @endphp
                <div class="stat-number">{{ $totalSemuaVendor }}</div>
                <div class="stat-label">Vendor Aktif</div>
            </div>
        </div>
    </div>

    {{-- ═══════════ VIDEO K3 PLN ═══════════ --}}
    <section id="video" class="video-section">
        <div class="video-inner">
            {{-- Teks kiri --}}
            <div class="video-text">
                <div class="video-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    Induction K3 PLN Nusantara Power UP Sengkang
                </div>
                <h2 class="video-title">Keselamatan &amp; Kesehatan Kerja<br>PT PLN Nusantara Power UP Sengkang</h2>
                <p class="video-desc">
                    Komitmen kami terhadap K3 bukan sekadar regulasi — ini adalah budaya.
                    Setiap karyawan adalah garis pertahanan pertama dalam menciptakan
                    lingkungan kerja yang aman dan sehat.
                </p>

                {{-- Poin keunggulan --}}
                <div style="margin-top:28px;display:flex;flex-direction:column;gap:12px;">
                    @php
                    $points = [
                        ['icon'=>'🛡️','text'=>'Zero Accident – target nasional K3'],
                        ['icon'=>'📋','text'=>'Audit K3 rutin & tersertifikasi'],
                        ['icon'=>'👷','text'=>'Pelatihan keselamatan seluruh pegawai'],
                    ];
                    @endphp
                    @foreach($points as $pt)
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:20px;">{{ $pt['icon'] }}</span>
                        <span style="color:#bfdbfe;font-size:14px;">{{ $pt['text'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Video player kanan --}}
            <div class="video-player-wrap">
                <div class="video-frame-outer">
                    <div class="video-ratio">
                        <iframe
                            src="https://www.youtube.com/embed/dQw4w9WgXcQ?rel=0&modestbranding=1"
                            title="Video K3 PLN Nusantara Power"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
                <p class="video-playlist-hint" style="color:#4b7ab5">
                    📺 Video resmi PT PLN Nusantara Power Unit Pembangkitan Sengkang
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════ ARTIKEL K3 ═══════════ --}}
    <section id="artikel" class="section">
        <div class="section-inner">
            <div class="section-header">
                <h2 class="section-title">Artikel K3 Terbaru</h2>
                <div class="section-line"></div>
            </div>

            @if($articles->isNotEmpty())
            <div class="articles-grid">
                @foreach($articles as $article)
                <a href="{{ route('artikel.show', $article->id) }}" class="article-card">
                    <div class="article-img-wrap">
                        @if($article->thumbnail)
                        <img src="{{ Storage::url($article->thumbnail) }}"
                             alt="{{ $article->title }}"
                             class="article-img"
                             loading="lazy">
                        @else
                        <div class="article-img-placeholder">
                            <svg width="48" height="48" fill="none" stroke="#93c5fd" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="article-body">
                        <span class="article-cat cat-{{ $article->category }}">{{ $article->category }}</span>
                        <h3 class="article-title">{{ $article->title }}</h3>
                        @if($article->published_at)
                        <div class="article-date">{{ $article->published_at->translatedFormat('d M Y') }}</div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:48px 0;color:#9ca3af">Belum ada artikel yang diterbitkan.</div>
            @endif
        </div>
    </section>

    {{-- ═══════════ VENDOR K3 (UPDATE TAB) ═══════════ --}}
    <section id="vendor" class="section section-alt">
        <div class="section-inner">
            <div class="section-header">
                <h2 class="section-title">Vendor K3</h2>
                <div class="section-line"></div>
            </div>

            {{-- Tab switcher --}}
            <div class="flex gap-3 mb-8 flex-wrap">
                <button id="tab-wpo-btn" onclick="switchVendorTab('wpo')"
                        class="vendor-tab-btn flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition border-2 border-[#003D7C] bg-[#003D7C] text-white">
                    🏭 Vendor WPO PLUS
                    <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">{{ isset($vendorsWpo) ? $vendorsWpo->count() : 0 }}</span>
                </button>
                <button id="tab-gate-btn" onclick="switchVendorTab('gate')"
                        class="vendor-tab-btn flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition border-2 border-gray-300 bg-white text-gray-600 hover:border-amber-400">
                    🔐 Registrasi Gate Access
                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-0.5 rounded-full">{{ isset($vendorsGate) ? $vendorsGate->count() : 0 }}</span>
                </button>
            </div>

            {{-- ─── WPO PLUS ──────────────────────────────────── --}}
            <div id="vendor-tab-wpo">
                @if(isset($vendorsWpo) && $vendorsWpo->isNotEmpty())
                <div class="vendors-grid">
                    @foreach($vendorsWpo as $vendor)
                    {{-- Memparsing JSON Pekerja --}}
                    @php
                        $pekerjaWpoData = is_string($vendor->pekerja_json)
                                          ? json_decode($vendor->pekerja_json, true)
                                          : ($vendor->pekerja_json ?? []);

                        $formattedPekerjaWpo = is_array($pekerjaWpoData) ? array_map(function($p) {
                            return [
                                'nama' => $p['nama'] ?? 'Tanpa Nama',
                            ];
                        }, $pekerjaWpoData) : [];
                    @endphp

                    <div class="vendor-card cursor-pointer hover:shadow-lg transition"
                         onclick='openVendorPopup({{ json_encode([
                             "type"           => "wpo",
                             "nama"           => $vendor->nama_vendor,
                             "pekerjaan"      => $vendor->nama_pekerjaan ?? $vendor->bidang_kerja,
                             "tanggal_mulai"  => $vendor->tanggal_mulai?->format("d/m/Y") ?? "-",
                             "tanggal_selesai"=> $vendor->tanggal_selesai?->format("d/m/Y") ?? "-",
                             "kontak"         => $vendor->kontak,
                             "email"          => $vendor->email,
                             "pekerjas"       => $formattedPekerjaWpo,
                         ]) }})'>
                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
                            <div class="vendor-icon">
                                <svg width="20" height="20" fill="none" stroke="#1d4ed8" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="vendor-name">{{ $vendor->nama_vendor }}</div>
                                <div class="vendor-bidang">{{ $vendor->nama_pekerjaan ?? $vendor->bidang_kerja }}</div>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">WPO</span>
                        </div>
                        @if($vendor->tanggal_mulai)
                        <div style="font-size:12px;color:#9ca3af;margin-top:6px">
                            📅 {{ $vendor->tanggal_mulai->format('d/m/Y') }} – {{ $vendor->tanggal_selesai?->format('d/m/Y') ?? '—' }}
                        </div>
                        @endif

                        <div style="font-size:12px;color:#6b7280;margin-top:4px">
                            👷 {{ count($formattedPekerjaWpo) }} pekerja terdaftar
                        </div>

                        <div style="font-size:11px;color:#9ca3af;margin-top:4px">Klik untuk detail →</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align:center;padding:40px;color:#9ca3af">Belum ada data vendor WPO PLUS.</div>
                @endif
            </div>

            {{-- ─── GATE ACCESS ────────────────────────────────── --}}
            <div id="vendor-tab-gate" class="hidden">
                @if(isset($vendorsGate) && $vendorsGate->isNotEmpty())
                <div class="vendors-grid">
                    @foreach($vendorsGate as $reg)
                    @php
                        $pekerjaData = $reg->pekerjasLulus->map(fn($p) => [
                            'nama'         => $p->nama_pekerja,
                        ])->toArray();
                    @endphp
                    <div class="vendor-card cursor-pointer hover:shadow-lg transition"
                         onclick='openVendorPopup({{ json_encode([
                             "type"           => "gate",
                             "nama"           => $reg->nama_perusahaan,
                             "pekerjaan"      => $reg->nama_pekerjaan,
                             "tanggal_mulai"  => $reg->tanggal_mulai->format("d/m/Y"),
                             "tanggal_selesai"=> $reg->tanggal_selesai->format("d/m/Y"),
                             "kontak"         => $reg->no_wa_pic,
                             "pekerjas"       => $pekerjaData,
                         ]) }})'>
                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
                            <div class="vendor-icon" style="background:#fef3c7">
                                <svg width="20" height="20" fill="none" stroke="#d97706" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="vendor-name">{{ $reg->nama_perusahaan }}</div>
                                <div class="vendor-bidang">{{ Str::limit($reg->nama_pekerjaan, 50) }}</div>
                            </div>
                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">GATE</span>
                        </div>
                        <div style="font-size:12px;color:#9ca3af;margin-top:4px">
                            📅 {{ $reg->tanggal_mulai->format('d/m/Y') }} – {{ $reg->tanggal_selesai->format('d/m/Y') }}
                        </div>
                        <div style="font-size:12px;color:#6b7280;margin-top:4px">
                            👷 {{ $reg->pekerjasLulus->count() }} pekerja terdaftar
                        </div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:4px">Klik untuk detail →</div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align:center;padding:40px 0">
                    <div style="font-size:3rem;margin-bottom:12px">🔐</div>
                    <div style="color:#374151;font-weight:600">Belum ada registrasi gate access aktif.</div>
                    <a href="/vendor/registrasi"
                       style="display:inline-block;margin-top:16px;background:#003D7C;color:#fff;padding:10px 24px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none">
                        Daftar Sekarang →
                    </a>
                </div>
                @endif
            </div>

            {{-- Flow (kalau ada) --}}
            @if(isset($flows) && $flows->isNotEmpty())
            <div style="margin-top:32px">
                <h3 style="font-weight:700;color:#374151;font-size:1.1rem;margin-bottom:16px">Diagram Alur Vendor</h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
                    @foreach($flows as $flow)
                    <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06)">
                        <img src="{{ Storage::url($flow->image_path) }}" alt="Diagram Alur" style="width:100%;display:block" loading="lazy">
                        @if($flow->keterangan)
                        <div style="padding:12px;font-size:13px;color:#6b7280">{{ $flow->keterangan }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>

    {{-- ═══════════ PATROL IZAT & TEMUAN OPEN ═══════════ --}}
    <section id="patrol" class="section">
        <div class="section-inner">
            <div class="section-header">
                <h2 class="section-title">Patrol iZAT & Temuan Open</h2>
                <div class="section-line"></div>
            </div>

            {{-- Grid Container 2 Kolom --}}
            <div class="patrol-temuan-grid">

                {{-- KOLOM KIRI: PATROL IZAT --}}
                <div>
                    @if($patrolPeriode && $patrolPeriode->jadwals->isNotEmpty())
                    <div class="patrol-card-main">
                        {{-- Header kartu --}}
                        <div class="patrol-card-header">
                            <div class="patrol-header-left">
                                <div class="patrol-icon">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="patrol-title">Jadwal Safety Patrol iZAT</div>
                                    <div class="patrol-subtitle">{{ $patrolBulan }} {{ $patrolTahun }}</div>
                                </div>
                            </div>
                            @php
                                // Ambil batas awal dan akhir minggu ini
                                $startOfWeek = now()->startOfWeek()->startOfDay();
                                $endOfWeek   = now()->endOfWeek()->endOfDay();

                                // Filter dari SEMUA jadwal di bulan ini, HANYA MINGGU INI SAJA
                                $jadwalMingguIni = $patrolPeriode->jadwals->filter(function ($jadwal) use ($startOfWeek, $endOfWeek) {
                                    return $jadwal->tanggal_patrol->between($startOfWeek, $endOfWeek);
                                });

                                // Hitung total dan yang sudah lapor dari filter tersebut
                                $totalMingguIni  = $jadwalMingguIni->count();
                                $sudahLapor      = $jadwalMingguIni->where('sudah_lapor', true)->count();
                            @endphp
                            <div class="patrol-count-badge">
                                <span class="patrol-count-num">{{ $sudahLapor }}/{{ $totalMingguIni }}</span>
                                <span class="patrol-count-label">lapor</span>
                            </div>
                        </div>

                        {{-- Sub-header: range tanggal minggu ini --}}
                        <div class="patrol-week-bar">
                            <span class="patrol-week-label">Petugas bertugas minggu ini</span>
                            <span class="patrol-week-range">{{ $patrolMingguRange }}</span>
                        </div>

                        {{-- Tabel --}}
                        <div class="patrol-table-wrap">
                            <table class="patrol-table">
                                <thead>
                                    <tr>
                                        <th class="col-no">NO</th>
                                        <th class="col-nama">NAMA PETUGAS</th>
                                        <th class="col-hari">HARI / TGL</th>
                                        <th class="col-status">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwalMingguIni->values() as $i => $jadwal)
                                    <tr class="{{ $i % 2 === 0 ? 'row-even' : 'row-odd' }}">
                                        <td class="col-no">{{ $i + 1 }}</td>
                                        <td class="col-nama">
                                            {{ $jadwal->nama_petugas }}
                                            @if($jadwal->lokasi_unit)
                                            <div class="mt-1"><span class="unit-badge">{{ $jadwal->lokasi_unit }}</span></div>
                                            @endif
                                        </td>
                                        <td class="col-hari">
                                            <span class="hari-label">{{ $jadwal->nama_hari }}</span>
                                            <span class="tgl-label">{{ $jadwal->tanggal_patrol->format('d/m') }}</span>
                                        </td>
                                        <td class="col-status">
                                            @if($jadwal->sudah_lapor)
                                            <span class="chip-done">
                                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                Sudah
                                            </span>
                                            @else
                                            <span class="chip-pending">
                                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                                                Belum
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" style="text-align:center;padding:24px;color:#9ca3af;font-size:13px">
                                            Tidak ada jadwal patrol minggu ini.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Progress --}}
                        @if($totalMingguIni > 0)
                        <div class="patrol-progress">
                            <span class="patrol-prog-label">Progress minggu ini</span>
                            <div class="patrol-prog-wrap">
                                <div class="patrol-prog-bar" style="width:{{ round(($sudahLapor / $totalMingguIni) * 100) }}%"></div>
                            </div>
                            <span class="patrol-prog-pct {{ $sudahLapor === $totalMingguIni ? 'text-green' : 'text-gray' }}">
                                {{ $sudahLapor }}/{{ $totalMingguIni }} ({{ round(($sudahLapor / $totalMingguIni) * 100) }}%)
                            </span>
                        </div>
                        @endif

                        {{-- Footer --}}
                        <div class="patrol-footer">
                            <div class="patrol-salam">Semangat Pagi Power People — Salam Safety!</div>
                        </div>
                    </div>
                    @else
                    <div class="patrol-card-main" style="padding:48px 20px;text-align:center">
                        <div style="font-size:3rem;margin-bottom:12px">📋</div>
                        <div style="font-weight:600;color:#374151">Tidak ada jadwal patrol untuk bulan ini</div>
                        <div style="color:#9ca3af;font-size:13px;margin-top:6px">{{ $patrolBulan }} {{ $patrolTahun }}</div>
                    </div>
                    @endif
                </div>

                {{-- KOLOM KANAN: TEMUAN OPEN --}}
                <div>
                    <div class="temuan-card">
                        <div class="temuan-header">
                            <div class="temuan-icon">
                                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="temuan-title">Jumlah Temuan Open</div>
                                <div class="temuan-sub">Status temuan K3 per bidang</div>
                            </div>
                        </div>

                        @if(isset($temuan_opens) && $temuan_opens->isNotEmpty())
                        <table class="temuan-table">
                            <thead>
                                <tr>
                                    <th class="no">NO</th>
                                    <th>BIDANG</th>
                                    <th class="jml">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($temuan_opens as $i => $t)
                                <tr class="{{ $i % 2 === 0 ? 'r-even' : 'r-odd' }}">
                                    <td class="no">{{ $i + 1 }}</td>
                                    <td class="bidang">{{ $t->bidang }}</td>
                                    <td class="jml">
                                        <span class="jml-badge {{ $t->jumlah_temuan > 10 ? 'danger' : ($t->jumlah_temuan > 5 ? 'warn' : 'ok') }}">
                                            {{ $t->jumlah_temuan }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="text-align:right;font-size:11px;font-weight:700;padding:8px 12px;color:#374151">Total Temuan:</td>
                                    <td class="jml">
                                        <span class="jml-badge total">{{ $temuan_opens->sum('jumlah_temuan') }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        @else
                        <div style="padding:32px;text-align:center;color:#9ca3af;font-size:13px">
                            Tidak ada temuan open saat ini.
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════ FOOTER ═══════════ --}}
    <footer class="footer">
        <div class="footer-inner">
            <div>
                <div class="footer-heading">SAKTI K3</div>
                <div class="footer-text">
                    PT PLN Nusantara Power<br>
                    Unit Pembangkitan Sengkang<br>
                    {{-- Link Google Maps --}}
                    <a href="https://www.google.com/maps/search/?api=1&query=PT+PLN+Nusantara+Power+Unit+Pembangkitan+Sengkang,+Jalan+PLTGU+Sengkang,+Desa+Patila,+Kecamatan+Pammana,+Kabupaten+Wajo,+90971"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="hover:text-[#FFC72C] transition-colors duration-200"
                       style="text-decoration: none; color: inherit;">
                       Jalan PLTGU Sengkang, Desa Patila, Kecamatan Pammana, Kabupaten Wajo, 90971
                    </a>
                </div>
            </div>
            <div>
                <div class="footer-heading">Akses Panel</div>
                <a href="/admin" class="footer-link">Panel Admin K3</a>
                <a href="/klinik" class="footer-link">Panel Klinik</a>
            </div>
            <div>
                <div class="footer-heading">Darurat K3</div>
                <div class="footer-text">
                    Hubungi Tim K3 segera jika terjadi insiden atau keadaan darurat.<br><br>

                    {{-- Link Email --}}
                    <strong style="color:#fbbf24">Email</strong> -
                    <a href="mailto:upsg@plnnusantarapower.co.id"
                       class="hover:text-[#FFC72C] transition-colors duration-200"
                       style="text-decoration: none; color: inherit;">
                       upsg@plnnusantarapower.co.id
                    </a><br>

                    {{-- Link Telepon --}}
                    <strong style="color:#fbbf24">Telp</strong> –
                    <a href="tel:+6248522228"
                       class="hover:text-[#FFC72C] transition-colors duration-200"
                       style="text-decoration: none; color: inherit;">
                       (+62 485) 22228
                    </a><br>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            © {{ date('Y') }} PT PLN Nusantara Power – Unit Pembangkitan Sengkang. All rights reserved.
        </div>
    </footer>

    {{-- ═══════════ POPUP DETAIL VENDOR ═══════════ --}}
    <div id="vendor-popup" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" onclick="if(event.target===this)closeVendorPopup()">
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="bg-[#003D7C] text-white p-5 rounded-t-2xl flex items-start justify-between">
                <div>
                    <div id="popup-type-badge" class="text-xs font-semibold px-2 py-1 rounded-full bg-white/20 inline-block mb-2">—</div>
                    <div id="popup-nama" class="font-bold text-lg leading-tight">—</div>
                    <div id="popup-pekerjaan" class="text-blue-200 text-sm mt-1">—</div>
                </div>
                <button onclick="closeVendorPopup()" class="text-white/70 hover:text-white text-2xl leading-none ml-4 flex-shrink-0">×</button>
            </div>

            <div class="p-5">
                <div class="bg-blue-50 rounded-xl p-4 mb-5 flex items-center gap-3">
                    <span class="text-2xl">📅</span>
                    <div>
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-0.5">Durasi Pekerjaan</div>
                        <div class="font-bold text-gray-800">
                            <span id="popup-mulai">—</span> s/d <span id="popup-selesai">—</span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                        👷 Daftar Pekerja
                        <span id="popup-jumlah" class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">0</span>
                    </div>

                    <div class="bg-[#003D7C] text-white text-xs font-semibold rounded-t-xl overflow-hidden">
                        <div class="grid grid-cols-12 gap-0">
                            <div class="col-span-1 px-3 py-2 text-center">No</div>
                            <div class="col-span-5 px-3 py-2">Nama Pekerja</div>
                        </div>
                    </div>

                    <div id="popup-pekerja-list" class="border border-gray-200 rounded-b-xl overflow-hidden divide-y divide-gray-100">
                        {{-- Diisi JS --}}
                    </div>

                    <div id="popup-empty" class="hidden text-center py-8 text-gray-400 text-sm">
                        Belum ada pekerja terdaftar.
                    </div>
                </div>

                <div id="popup-kontak-wrap" class="mt-4 hidden">
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-1">Kontak</div>
                    <div id="popup-kontak" class="text-sm text-gray-700">—</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu
        function openDrawer() {
            document.getElementById('drawer').classList.add('open');
            document.getElementById('drawer-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            document.getElementById('drawer').classList.remove('open');
            document.getElementById('drawer-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Tutup drawer kalau layar diperbesar ke desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 900) closeDrawer();
        });

        // Show desktop nav on larger screens
        function checkScreen() {
            const isDesktop = window.innerWidth >= 900;
            document.getElementById('desktop-nav').style.display = isDesktop ? 'flex' : 'none';
        }
        checkScreen();
        window.addEventListener('resize', checkScreen);

        // Swiper banner
        new Swiper('.banner-swiper', {
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });

        // Tab Switcher Vendor
        function switchVendorTab(tab) {
            document.getElementById('vendor-tab-wpo').classList.toggle('hidden', tab !== 'wpo');
            document.getElementById('vendor-tab-gate').classList.toggle('hidden', tab !== 'gate');

            document.querySelectorAll('.vendor-tab-btn').forEach(btn => {
                btn.classList.remove('border-[#003D7C]','bg-[#003D7C]','text-white');
                btn.classList.add('border-gray-300','bg-white','text-gray-600');
            });

            const activeId = tab === 'wpo' ? 'tab-wpo-btn' : 'tab-gate-btn';
            const activeBtn = document.getElementById(activeId);
            activeBtn.classList.remove('border-gray-300','bg-white','text-gray-600');
            activeBtn.classList.add('border-[#003D7C]','bg-[#003D7C]','text-white');
        }

        // Vendor Popup Logic
        function openVendorPopup(data) {
            const badge = data.type === 'wpo' ? 'Vendor WPO PLUS' : 'Registrasi Gate Access';
            document.getElementById('popup-type-badge').textContent = badge;
            document.getElementById('popup-nama').textContent       = data.nama;
            document.getElementById('popup-pekerjaan').textContent  = data.pekerjaan;
            document.getElementById('popup-mulai').textContent      = data.tanggal_mulai;
            document.getElementById('popup-selesai').textContent    = data.tanggal_selesai;

            const list     = document.getElementById('popup-pekerja-list');
            const empty    = document.getElementById('popup-empty');
            const jmlBadge = document.getElementById('popup-jumlah');

            if (data.pekerjas && data.pekerjas.length > 0) {
                jmlBadge.textContent = data.pekerjas.length;
                empty.classList.add('hidden');
                list.classList.remove('hidden');
                list.innerHTML = data.pekerjas.map((p, i) => {
                    const suketColor = p.status_suket === 'expired' ? '#dc2626'
                                     : p.status_suket === 'soon'    ? '#d97706' : '';
                    const suketText  = p.exp_suket
                        ? `<span style="color:${suketColor};font-weight:${suketColor?'600':'400'}">${p.exp_suket}</span>`
                        : '—';
                    return `<div class="grid grid-cols-12 gap-0 text-sm ${i%2===1?'bg-gray-50':'bg-white'}">
                        <div class="col-span-1 px-3 py-2.5 text-center text-gray-500 font-medium">${i+1}.</div>
                        <div class="col-span-5 px-3 py-2.5 font-medium text-gray-800">${p.nama}</div>
                    </div>`;
                }).join('');
            } else {
                jmlBadge.textContent = '0';
                list.innerHTML = '';
                empty.classList.remove('hidden');
            }

            if (data.kontak) {
                document.getElementById('popup-kontak').textContent = data.kontak;
                document.getElementById('popup-kontak-wrap').classList.remove('hidden');
            } else {
                document.getElementById('popup-kontak-wrap').classList.add('hidden');
            }

            document.getElementById('vendor-popup').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeVendorPopup() {
            document.getElementById('vendor-popup').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>
