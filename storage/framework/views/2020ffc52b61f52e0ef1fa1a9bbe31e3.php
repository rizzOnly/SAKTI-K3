<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D-SAVE</title>
    <link rel="icon" href="<?php echo e(asset('images/logo-sakti.png')); ?>" type="image/png">
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
        .nav-links { display: flex; gap: 24px; align-items: center; }
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
        .banner-slide { position: relative; width: 100%;background: #f8fafc;line-height: 0; }
        .banner-slide img { width: 100%; height: auto;display: block; }
        .banner-overlay {position: absolute;inset: 0;background: linear-gradient(to top, rgba(0,0,0,.55) 0%, transparent 60%);pointer-events: none; }
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
        .article-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; z-index: 1; transition: transform .3s;}
        .article-card:hover .article-img { transform: scale(1.04); }
        .article-img-wrap {position: relative; width: 100%; aspect-ratio: 905 / 1280; overflow: hidden; background: #ffffff;}
        .article-img-bg { display: none; }
        .article-img-placeholder { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
        .article-body { padding: 16px; flex: 1; }
        .article-cat { display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 11px; font-weight: 600; margin-bottom: 8px; text-transform: capitalize; }
        .cat-kampanye { background: #fee2e2; color: #991b1b; }
        .cat-berita   { background: #dbeafe; color: #1e40af; }
        .cat-panduan  { background: #dcfce7; color: #166534; }
        .cat-lainnya  { background: #fef3c7; color: #92400e; }
        .article-title { font-weight: 700; color: #111827; font-size: 15px; line-height: 1.45; margin: 0 0 8px; }
        .article-date  { color: #9ca3af; font-size: 12px; }

        /* ── Artikel Carousel (Desktop) & Show More (Mobile) ── */
        .artikel-desktop-carousel { display: block; }
        .artikel-mobile-grid { display: none; }

        .artikel-swiper { width: 100%; overflow: hidden; }
        .artikel-swiper .swiper-wrapper { display: flex; }
        .artikel-swiper .swiper-slide { flex-shrink: 0; width: calc(33.333% - 11px); margin-right: 16px; }
        @media (max-width: 900px) { .artikel-swiper .swiper-slide { width: calc(50% - 8px); } }
        @media (max-width: 560px) { .artikel-swiper .swiper-slide { width: 85%; } }

        .artikel-swiper-nav { display: flex; align-items: center; justify-content: center; gap: 12px; margin-top: 20px; }
        .artikel-swiper-btn { width: 38px; height: 38px; border-radius: 50%; border: 2px solid #003D7C; background: #fff; color: #003D7C; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; flex-shrink: 0; }
        .artikel-swiper-btn:hover { background: #003D7C; color: #fff; }
        .artikel-swiper-btn:disabled { opacity: .3; cursor: not-allowed; }
        .artikel-swiper-btn svg { width: 16px; height: 16px; }
        .artikel-swiper-dots { display: flex; gap: 6px; align-items: center; }
        .artikel-dot { width: 7px; height: 7px; border-radius: 50%; background: #d1d5db; transition: all .25s; cursor: pointer; }
        .artikel-dot.active { background: #003D7C; width: 20px; border-radius: 4px; }

        .artikel-card-hidden { display: none; }

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
        .patrol-table-wrap { overflow-x: auto; overflow-y: auto; max-height 280px; }
        .patrol-table thead tr { background:#25d366; position: sticky; top: 0; z-index: 1; }
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

        /* ── Vendor Carousel (Desktop) & Show More (Mobile) ── */
        .vendor-swiper-wrap { position: relative; }
        .vendor-swiper { width: 100%; overflow: hidden; }
        .vendor-swiper .swiper-wrapper { display: flex; }
        .vendor-swiper .swiper-slide { flex-shrink: 0; width: calc(33.333% - 11px); margin-right: 16px; }
        @media (max-width: 900px) { .vendor-swiper .swiper-slide { width: calc(50% - 8px); } }
        @media (max-width: 560px) { .vendor-swiper .swiper-slide { width: 85%; } }

        .vendor-swiper-nav { display: flex; align-items: center; justify-content: center; gap: 12px; margin-top: 20px; }
        .vendor-swiper-btn { width: 38px; height: 38px; border-radius: 50%; border: 2px solid #003D7C; background: #fff; color: #003D7C; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s; flex-shrink: 0; }
        .vendor-swiper-btn:hover { background: #003D7C; color: #fff; }
        .vendor-swiper-btn:disabled { opacity: .3; cursor: not-allowed; }
        .vendor-swiper-btn svg { width: 16px; height: 16px; }
        .vendor-swiper-dots { display: flex; gap: 6px; align-items: center; }
        .vendor-dot { width: 7px; height: 7px; border-radius: 50%; background: #d1d5db; transition: all .25s; cursor: pointer; }
        .vendor-dot.active { background: #003D7C; width: 20px; border-radius: 4px; }

        /* Mobile: show more */
        .vendor-mobile-grid { display: none; }
        @media (max-width: 900px) {
            .vendor-desktop-carousel { display: none; }
            .vendor-mobile-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
        }
        .vendor-card-hidden { display: none; }
        .btn-show-more { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; margin-top: 16px; padding: 13px; border-radius: 14px; border: 2px dashed #d1d5db; background: transparent; color: #6b7280; font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s; font-family: inherit; }
        .btn-show-more:hover { border-color: #003D7C; color: #003D7C; background: #eff6ff; }
        .btn-show-more svg { width: 16px; height: 16px; transition: transform .3s; }
        .btn-show-more.expanded svg { transform: rotate(180deg); }
    </style>
</head>
<body>

    
    <header>
        <nav class="navbar">
            <div class="nav-inner">
                <a href="/" class="nav-brand">
                    <img src="<?php echo e(asset('images/logo-sakti.png')); ?>" alt="Logo D-SAVE" style="height: 44px; width: auto; object-fit: contain;">
                    <div>
                        <div class="nav-title">D-SAVE</div>
                        <div class="nav-sub">Platform Terpadu K3 & Manajemen APD</div>
                    </div>
                </a>

                
                <div class="nav-links" id="desktop-nav">
                    <a href="#beranda">BERANDA</a>
                    <a href="#video">PROFIL</a>
                    <a href="#artikel">ARTIKEL</a>
                    <a href="#vendor">VENDOR</a>
                    <a href="#fit-to-work">FIT TO WORK</a>
                    <a href="#patrol">PATROL</a>

                    
                    <a href="<?php echo e(route('login')); ?>"
                       style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.12);
                              color:#fff;padding:6px 14px;border-radius:8px;font-size:13px;font-weight:700;
                              text-decoration:none;border:1.5px solid rgba(255,255,255,.25);
                              transition:background .2s,border-color .2s;"
                       onmouseover="this.style.background='rgba(255,255,255,.22)';this.style.borderColor='rgba(255,255,255,.5)'"
                       onmouseout="this.style.background='rgba(255,255,255,.12)';this.style.borderColor='rgba(255,255,255,.25)'">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" viewBox="0 0 24 24">
                            <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                        LOGIN
                    </a>
                </div>

                
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

        
        <div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

        
        <div class="drawer" id="drawer">

            
            <div class="drawer-header">
                <img src="<?php echo e(asset('images/logo-sakti.png')); ?>" alt="Logo D-SAVE" style="height: 44px; width: auto; object-fit: contain;">
                <div>
                    <div class="drawer-brand-title">D-SAVE</div>
                    <div class="drawer-brand-sub">Platform Terpadu K3 & Manajemen APD</div>
                </div>
                
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

            
            <nav class="drawer-nav">
                
                <a href="<?php echo e(route('login')); ?>"
                   style="background:rgba(255,199,44,.12);border-left-color:#FFC72C !important;
                          border-bottom:1px solid rgba(255,255,255,.08);">
                    <span class="drawer-nav-icon" style="background:rgba(255,199,44,.2);">
                        <svg width="15" height="15" fill="none" stroke="#FFC72C" stroke-width="2"
                             stroke-linecap="round" viewBox="0 0 24 24">
                            <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
                            <polyline points="10 17 15 12 10 7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                    </span>
                    <span style="color:#FFC72C;font-weight:700;">LOGIN PANEL</span>
                </a>

                <a href="#beranda" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">🏠</span> BERANDA
                </a>
                <a href="#video" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">▶️</span> PROFIL
                </a>
                <a href="#artikel" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">📰</span> ARTIKEL K3
                </a>
                <a href="#vendor" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">🏢</span> VENDOR
                </a>
                <a href="#patrol" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">👁</span> PATROL iZAT
                </a>
                <a href="#fit-to-work" onclick="closeDrawer()">
                    <span class="drawer-nav-icon">⚠️</span> FIT TO WORK
                </a>
            </nav>

            <div class="drawer-divider"></div>

            
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

    
    <section id="beranda">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banners->isNotEmpty()): ?>
        <div class="swiper banner-swiper">
            <div class="swiper-wrapper">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide banner-slide">
                    <img src="<?php echo e(Storage::url($banner->image_path)); ?>" alt="<?php echo e($banner->title ?? 'Banner K3'); ?>" loading="eager">
                    <div class="banner-overlay"></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->title): ?>
                    <div class="banner-caption">
                        <h2><?php echo e($banner->title); ?></h2>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        <?php else: ?>
        <div class="hero-fallback">
            <h1>Sistem Informasi K3</h1>
            <p>PT PLN Nusantara Power – Unit Pembangkitan Sengkang</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    
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

            <a href="<?php echo e(route('fit-to-work.form')); ?>" class="quick-btn" style="background:#dc2626;color:#fff">
                <span class="text-base leading-none">⚠️</span> Fit to Work
            </a>
        </div>
    </div>

    
    <div class="stats-bar">
        <div class="stats-inner">
            <div class="stat-box">
                <div class="stat-number"><?php echo e(\App\Models\ApdItem::count()); ?></div>
                <div class="stat-label">Jenis APD</div>
            </div>
            <div class="stat-box">
                <div class="stat-number"><?php echo e(\App\Models\CmsArticle::where('is_published', true)->count()); ?></div>
                <div class="stat-label">Artikel K3</div>
            </div>
            <div class="stat-box">
                
                <?php
                    $totalVendorWpo = \App\Models\CmsVendor::where('is_active', true)->count();
                    $totalVendorGate = \App\Models\VendorRegistrasi::aktifDanBerlaku()->count();
                    $totalSemuaVendor = $totalVendorWpo + $totalVendorGate;
                ?>
                <div class="stat-number"><?php echo e($totalSemuaVendor); ?></div>
                <div class="stat-label">Vendor Aktif</div>
            </div>
        </div>
    </div>

    
    <section id="video" class="video-section">
        <div class="video-inner">
            
            <div class="video-text">
                <div class="video-badge">
                    Profil Unit PT PLN Nusantara Power UP Sengkang
                </div>
                <h2 class="video-title">Unit Pembangkit Listrik Tenaga Gas Uap<br> PLN Nusantara Power UP Sengkang</h2>
                <p class="video-desc" style="text-align: justify;">
                    PT PLN Nusantara Power UP Sengkang merupakan unit pembangkit
                    listrik berbahan bakar gas alam (Natural Gas) yang berlokasi di Desa Patilla, Kecamatan
                    Pammana, Kabupaten Wajo, Sulawesi Selatan. Berdiri di atas lahan seluas lebih dari 35
                    hektare, unit ini menjadi salah satu tulang punggung kelistrikan kawasan timur Indonesia.
                </p>

                
                <div style="color:#bfdbfe;font-size:14px;text-align:justify;">
                    <?php
                    $points = [
                        ['icon'=>'⚡','text'=>'Kapasitas total 315 MW — Blok 1 (135 MW, COD 1998) & Blok 2 (180 MW, COD 2013)'],
                        ['icon'=>'📍','text'=>'Berlokasi strategis di Kab. Wajo, Sulawesi Selatan, di atas lahan +35 Hektare'],
                        ['icon'=>'🔧','text'=>'Dioperasikan oleh PLN Nusantara Power sejak Maret 2023 setelah akuisisi aset PLTGU Sengkang'],
                    ];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <span style="font-size:20px;"><?php echo e($pt['icon']); ?></span>
                        <span style="color:#bfdbfe;font-size:14px;"><?php echo e($pt['text']); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="video-player-wrap">
                <div class="video-frame-outer">
                    <div class="video-ratio">
                        <img
                            src="<?php echo e(asset('images/pln.png')); ?>"
                            alt="Profil K3 PLN Sengkang"
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;"
                            loading="lazy"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section id="artikel" class="section">
        <div class="section-inner">

            
            <div class="section-header" style="display: flex; align-items: center; gap: 16px; margin-bottom: 30px;">
                <h2 class="section-title" style="margin: 0; white-space: nowrap;">Artikel K3 Terbaru</h2>
                <div class="section-line" style="flex-grow: 1; height: 2px; background-color: #FFC72C;"></div>
                <a href="<?php echo e(route('artikel.index')); ?>"
                   style="display: inline-flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 700; color: #003D7C; text-decoration: none; padding: 6px 16px; border: 2px solid #003D7C; border-radius: 9999px; transition: all 0.2s; white-space: nowrap;"
                   onmouseover="this.style.backgroundColor='#003D7C'; this.style.color='#ffffff';"
                   onmouseout="this.style.backgroundColor='transparent'; this.style.color='#003D7C';">
                    Lihat Semua
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articles->isNotEmpty()): ?>

            
            <div class="artikel-desktop-carousel">
                <div class="artikel-swiper" id="artikel-swiper">
                    <div class="swiper-wrapper" id="artikel-swiper-track">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <a href="<?php echo e(route('artikel.show', $article->id)); ?>" class="article-card">
                                <div class="article-img-wrap">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->thumbnail): ?>
                                    
                                    <img src="<?php echo e(Storage::url($article->thumbnail)); ?>"
                                         alt=""
                                         class="article-img-bg"
                                         aria-hidden="true"
                                         loading="lazy">
                                    
                                    <img src="<?php echo e(Storage::url($article->thumbnail)); ?>"
                                         alt="<?php echo e($article->title); ?>"
                                         class="article-img"
                                         loading="lazy">
                                    <?php else: ?>
                                    <div class="article-img-placeholder">
                                        <svg width="48" height="48" fill="none" stroke="#93c5fd" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="article-body">
                                    <span class="article-cat cat-<?php echo e($article->category); ?>"><?php echo e($article->category); ?></span>
                                    <h3 class="article-title"><?php echo e($article->title); ?></h3>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->published_at): ?>
                                    <div class="article-date"><?php echo e($article->published_at->translatedFormat('d M Y')); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="artikel-swiper-nav">
                    <button class="artikel-swiper-btn" id="artikel-prev" onclick="artikelSwipe(-1)" aria-label="Sebelumnya">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <div class="artikel-swiper-dots" id="artikel-dots"></div>
                    <button class="artikel-swiper-btn" id="artikel-next" onclick="artikelSwipe(1)" aria-label="Berikutnya">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            
            <div class="artikel-mobile-grid" id="artikel-mobile-grid">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('artikel.show', $article->id)); ?>"
                   class="article-card <?php echo e($idx >= 3 ? 'artikel-card-hidden' : ''); ?>">
                    <div class="article-img-wrap">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->thumbnail): ?>
                        
                        <img src="<?php echo e(Storage::url($article->thumbnail)); ?>"
                             alt=""
                             class="article-img-bg"
                             aria-hidden="true"
                             loading="lazy">
                        
                        <img src="<?php echo e(Storage::url($article->thumbnail)); ?>"
                             alt="<?php echo e($article->title); ?>"
                             class="article-img"
                             loading="lazy">
                        <?php else: ?>
                        <div class="article-img-placeholder">
                            <svg width="48" height="48" fill="none" stroke="#93c5fd" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="article-body">
                        <span class="article-cat cat-<?php echo e($article->category); ?>"><?php echo e($article->category); ?></span>
                        <h3 class="article-title"><?php echo e($article->title); ?></h3>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->published_at): ?>
                        <div class="article-date"><?php echo e($article->published_at->translatedFormat('d M Y')); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articles->count() > 3): ?>
                <button class="btn-show-more" id="artikel-show-more" onclick="toggleArtikelShowMore()">
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    Tampilkan <?php echo e($articles->count() - 3); ?> artikel lainnya
                </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php else: ?>
            <div style="text-align:center;padding:48px 0;color:#9ca3af">Belum ada artikel yang diterbitkan.</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>
    </section>

    
    <section id="vendor" class="section section-alt">
        <div class="section-inner">
            <div class="section-header">
                <h2 class="section-title">Vendor K3</h2>
                <div class="section-line"></div>
            </div>

            
            <div class="flex gap-3 mb-8 flex-wrap">
                <button id="tab-wpo-btn" onclick="switchVendorTab('wpo')"
                        class="vendor-tab-btn flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition border-2 border-[#003D7C] bg-[#003D7C] text-white">
                    🏭 Vendor WPO PLUS
                    <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full"><?php echo e(isset($vendorsWpo) ? $vendorsWpo->count() : 0); ?></span>
                </button>
                <button id="tab-gate-btn" onclick="switchVendorTab('gate')"
                        class="vendor-tab-btn flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold transition border-2 border-gray-300 bg-white text-gray-600 hover:border-amber-400">
                    🔐 Registrasi Gate Access
                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-0.5 rounded-full"><?php echo e(isset($vendorsGate) ? $vendorsGate->count() : 0); ?></span>
                </button>
            </div>

            
            <div id="vendor-tab-wpo">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($vendorsWpo) && $vendorsWpo->isNotEmpty()): ?>

                
                <div class="vendor-desktop-carousel">
                    <div class="vendor-swiper-wrap">
                        <div class="vendor-swiper" id="wpo-swiper">
                            <div class="swiper-wrapper" id="wpo-swiper-track">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $vendorsWpo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $pekerjaWpoData = is_string($vendor->pekerja_json)
                                                      ? json_decode($vendor->pekerja_json, true)
                                                      : ($vendor->pekerja_json ?? []);
                                    $formattedPekerjaWpo = is_array($pekerjaWpoData) ? array_map(function($p) {
                                        return ['nama' => $p['nama'] ?? 'Tanpa Nama'];
                                    }, $pekerjaWpoData) : [];
                                ?>
                                <div class="swiper-slide">
                                    <div class="vendor-card cursor-pointer hover:shadow-lg transition"
                                         onclick='openVendorPopup(<?php echo e(json_encode([
                                             "type"           => "wpo",
                                             "nama"           => $vendor->nama_vendor,
                                             "pekerjaan"      => $vendor->nama_pekerjaan ?? $vendor->bidang_kerja,
                                             "tanggal_mulai"  => $vendor->tanggal_mulai?->format("d/m/Y") ?? "-",
                                             "tanggal_selesai"=> $vendor->tanggal_selesai?->format("d/m/Y") ?? "-",
                                             "kontak"         => $vendor->kontak,
                                             "email"          => $vendor->email,
                                             "pekerjas"       => $formattedPekerjaWpo,
                                         ])); ?>)'>
                                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
                                            <div class="vendor-icon">
                                                <svg width="20" height="20" fill="none" stroke="#1d4ed8" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1" style="min-width:0">
                                                <div class="vendor-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?php echo e($vendor->nama_vendor); ?></div>
                                                <div class="vendor-bidang" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?php echo e($vendor->nama_pekerjaan ?? $vendor->bidang_kerja); ?></div>
                                            </div>
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">WPO</span>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vendor->tanggal_mulai): ?>
                                        <div style="font-size:12px;color:#9ca3af;margin-top:6px">
                                            📅 <?php echo e($vendor->tanggal_mulai->format('d/m/Y')); ?> – <?php echo e($vendor->tanggal_selesai?->format('d/m/Y') ?? '—'); ?>

                                        </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div style="font-size:12px;color:#6b7280;margin-top:4px">
                                            👷 <?php echo e(count($formattedPekerjaWpo)); ?> pekerja terdaftar
                                        </div>
                                        <div style="font-size:11px;color:#9ca3af;margin-top:4px">Klik untuk detail →</div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        
                        <div class="vendor-swiper-nav">
                            <button class="vendor-swiper-btn" id="wpo-prev" onclick="vendorSwipe('wpo',-1)" aria-label="Sebelumnya">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <div class="vendor-swiper-dots" id="wpo-dots"></div>
                            <button class="vendor-swiper-btn" id="wpo-next" onclick="vendorSwipe('wpo',1)" aria-label="Berikutnya">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                
                <div class="vendor-mobile-grid" id="wpo-mobile-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $vendorsWpo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pekerjaWpoData = is_string($vendor->pekerja_json)
                                          ? json_decode($vendor->pekerja_json, true)
                                          : ($vendor->pekerja_json ?? []);
                        $formattedPekerjaWpo = is_array($pekerjaWpoData) ? array_map(function($p) {
                            return ['nama' => $p['nama'] ?? 'Tanpa Nama'];
                        }, $pekerjaWpoData) : [];
                    ?>
                    <div class="vendor-card cursor-pointer <?php echo e($idx >= 3 ? 'vendor-card-hidden' : ''); ?>"
                         onclick='openVendorPopup(<?php echo e(json_encode([
                             "type"           => "wpo",
                             "nama"           => $vendor->nama_vendor,
                             "pekerjaan"      => $vendor->nama_pekerjaan ?? $vendor->bidang_kerja,
                             "tanggal_mulai"  => $vendor->tanggal_mulai?->format("d/m/Y") ?? "-",
                             "tanggal_selesai"=> $vendor->tanggal_selesai?->format("d/m/Y") ?? "-",
                             "kontak"         => $vendor->kontak,
                             "email"          => $vendor->email,
                             "pekerjas"       => $formattedPekerjaWpo,
                         ])); ?>)'>
                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
                            <div class="vendor-icon">
                                <svg width="20" height="20" fill="none" stroke="#1d4ed8" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="vendor-name"><?php echo e($vendor->nama_vendor); ?></div>
                                <div class="vendor-bidang"><?php echo e($vendor->nama_pekerjaan ?? $vendor->bidang_kerja); ?></div>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">WPO</span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vendor->tanggal_mulai): ?>
                        <div style="font-size:12px;color:#9ca3af;margin-top:6px">
                            📅 <?php echo e($vendor->tanggal_mulai->format('d/m/Y')); ?> – <?php echo e($vendor->tanggal_selesai?->format('d/m/Y') ?? '—'); ?>

                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div style="font-size:12px;color:#6b7280;margin-top:4px">
                            👷 <?php echo e(count($formattedPekerjaWpo)); ?> pekerja terdaftar
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vendorsWpo->count() > 3): ?>
                    <button class="btn-show-more" id="wpo-show-more" onclick="toggleShowMore('wpo')">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        Tampilkan <?php echo e($vendorsWpo->count() - 3); ?> vendor lainnya
                    </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php else: ?>
                <div style="text-align:center;padding:40px;color:#9ca3af">Belum ada data vendor WPO PLUS.</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div id="vendor-tab-gate" class="hidden">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($vendorsGate) && $vendorsGate->isNotEmpty()): ?>

                
                <div class="vendor-desktop-carousel">
                    <div class="vendor-swiper-wrap">
                        <div class="vendor-swiper" id="gate-swiper">
                            <div class="swiper-wrapper" id="gate-swiper-track">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $vendorsGate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $pekerjaData = $reg->pekerjasLulus->map(fn($p) => ['nama' => $p->nama_pekerja])->toArray();
                                ?>
                                <div class="swiper-slide">
                                    <div class="vendor-card cursor-pointer hover:shadow-lg transition"
                                         onclick='openVendorPopup(<?php echo e(json_encode([
                                             "type"           => "gate",
                                             "nama"           => $reg->nama_perusahaan,
                                             "pekerjaan"      => $reg->nama_pekerjaan,
                                             "tanggal_mulai"  => $reg->tanggal_mulai->format("d/m/Y"),
                                             "tanggal_selesai"=> $reg->tanggal_selesai->format("d/m/Y"),
                                             "kontak"         => $reg->no_wa_pic,
                                             "pekerjas"       => $pekerjaData,
                                         ])); ?>)'>
                                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
                                            <div class="vendor-icon" style="background:#fef3c7">
                                                <svg width="20" height="20" fill="none" stroke="#d97706" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1" style="min-width:0">
                                                <div class="vendor-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?php echo e($reg->nama_perusahaan); ?></div>
                                                <div class="vendor-bidang" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?php echo e($reg->nama_pekerjaan); ?></div>
                                            </div>
                                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">GATE</span>
                                        </div>
                                        <div style="font-size:12px;color:#9ca3af;margin-top:4px">
                                            📅 <?php echo e($reg->tanggal_mulai->format('d/m/Y')); ?> – <?php echo e($reg->tanggal_selesai->format('d/m/Y')); ?>

                                        </div>
                                        <div style="font-size:12px;color:#6b7280;margin-top:4px">
                                            👷 <?php echo e($reg->pekerjasLulus->count()); ?> pekerja terdaftar
                                        </div>
                                        <div style="font-size:11px;color:#9ca3af;margin-top:4px">Klik untuk detail →</div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <div class="vendor-swiper-nav">
                            <button class="vendor-swiper-btn" id="gate-prev" onclick="vendorSwipe('gate',-1)" aria-label="Sebelumnya">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <div class="vendor-swiper-dots" id="gate-dots"></div>
                            <button class="vendor-swiper-btn" id="gate-next" onclick="vendorSwipe('gate',1)" aria-label="Berikutnya">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                
                <div class="vendor-mobile-grid" id="gate-mobile-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $vendorsGate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $pekerjaData = $reg->pekerjasLulus->map(fn($p) => ['nama' => $p->nama_pekerja])->toArray();
                    ?>
                    <div class="vendor-card cursor-pointer <?php echo e($idx >= 3 ? 'vendor-card-hidden' : ''); ?>"
                         onclick='openVendorPopup(<?php echo e(json_encode([
                             "type"           => "gate",
                             "nama"           => $reg->nama_perusahaan,
                             "pekerjaan"      => $reg->nama_pekerjaan,
                             "tanggal_mulai"  => $reg->tanggal_mulai->format("d/m/Y"),
                             "tanggal_selesai"=> $reg->tanggal_selesai->format("d/m/Y"),
                             "kontak"         => $reg->no_wa_pic,
                             "pekerjas"       => $pekerjaData,
                         ])); ?>)'>
                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
                            <div class="vendor-icon" style="background:#fef3c7">
                                <svg width="20" height="20" fill="none" stroke="#d97706" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="vendor-name"><?php echo e($reg->nama_perusahaan); ?></div>
                                <div class="vendor-bidang"><?php echo e(Str::limit($reg->nama_pekerjaan, 50)); ?></div>
                            </div>
                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-semibold flex-shrink-0">GATE</span>
                        </div>
                        <div style="font-size:12px;color:#9ca3af;margin-top:4px">
                            📅 <?php echo e($reg->tanggal_mulai->format('d/m/Y')); ?> – <?php echo e($reg->tanggal_selesai->format('d/m/Y')); ?>

                        </div>
                        <div style="font-size:12px;color:#6b7280;margin-top:4px">
                            👷 <?php echo e($reg->pekerjasLulus->count()); ?> pekerja terdaftar
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($vendorsGate->count() > 3): ?>
                    <button class="btn-show-more" id="gate-show-more" onclick="toggleShowMore('gate')">
                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        Tampilkan <?php echo e($vendorsGate->count() - 3); ?> vendor lainnya
                    </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php else: ?>
                <div style="text-align:center;padding:40px 0">
                    <div style="font-size:3rem;margin-bottom:12px">🔐</div>
                    <div style="color:#374151;font-weight:600">Belum ada registrasi gate access aktif.</div>
                    <a href="/vendor/registrasi" style="display:inline-block;margin-top:16px;background:#003D7C;color:#fff;padding:10px 24px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none">
                        Daftar Sekarang →
                    </a>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($flows) && $flows->isNotEmpty()): ?>
            <div style="margin-top:32px">
                <h3 style="font-weight:700;color:#374151;font-size:1.1rem;margin-bottom:16px">Diagram Alur Vendor</h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $flows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $flow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06)">
                        <img src="<?php echo e(Storage::url($flow->image_path)); ?>" alt="Diagram Alur" style="width:100%;display:block" loading="lazy">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flow->keterangan): ?>
                        <div style="padding:12px;font-size:13px;color:#6b7280"><?php echo e($flow->keterangan); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>
    </section>

    
    <section id="fit-to-work" class="section">
        <div class="section-inner">
            <div class="section-header">
                <h2 class="section-title">Fit to Work – Vendor Aktif</h2>
                <div class="section-line"></div>
            </div>

            <div class="mb-6 flex items-center justify-between flex-wrap gap-3">
                <p class="text-sm text-gray-500">
                    Daftar vendor / kontraktor yang telah dinyatakan <strong>Fit to Work</strong>
                    oleh dokter klinik untuk pekerjaan risiko tinggi.
                </p>
                <a href="<?php echo e(route('fit-to-work.form')); ?>"
                   class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow">
                    ⚠️ Daftar Fit to Work
                </a>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fitToWorkVendor->isNotEmpty()): ?>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fitToWorkVendor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $pekerjaList = $submission->pekerjas->map(fn($p) => [
                        'nama'           => $p->nama,
                        'jenis_kelamin'  => $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                        'status'         => $p->status,
                        'tanggal_periksa'=> $p->tanggal_periksa?->format('d/m/Y') ?? '-',
                        'dokter'         => $p->dokter_nama ?? '-',
                    ])->values()->toArray();
                ?>
                <div class="vendor-card cursor-pointer hover:shadow-lg transition"
                     onclick='openFtwPopup(<?php echo e(json_encode([
                         "nama"            => $submission->nama_perusahaan,
                         "pekerjaan"       => $submission->nama_pekerjaan,
                         "tanggal_mulai"   => $submission->tanggal_mulai->format("d/m/Y"),
                         "tanggal_selesai" => $submission->tanggal_selesai->format("d/m/Y"),
                         "pekerjas"        => $pekerjaList,
                     ])); ?>)'>

                    <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:8px">
                        
                        <div style="width:40px;height:40px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        
                        <div class="flex-1" style="min-width:0">
                            <div class="vendor-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                <?php echo e($submission->nama_perusahaan); ?>

                            </div>
                            <div class="vendor-bidang" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                <?php echo e($submission->nama_pekerjaan); ?>

                            </div>
                        </div>
                        <span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:700;padding:2px 8px;border-radius:999px;white-space:nowrap;flex-shrink:0">
                            ✅ FIT
                        </span>
                    </div>

                    <div style="font-size:12px;color:#9ca3af;margin-top:4px">
                        📅 <?php echo e($submission->tanggal_mulai->format('d/m/Y')); ?> – <?php echo e($submission->tanggal_selesai->format('d/m/Y')); ?>

                    </div>
                    <div style="font-size:12px;color:#6b7280;margin-top:4px">
                        👷 <?php echo e($submission->pekerjas->count()); ?> pekerja terdaftar
                    </div>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px">Klik untuk detail →</div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php else: ?>
            <div style="text-align:center;padding:48px 0;color:#9ca3af">
                <div style="font-size:3rem;margin-bottom:12px">🏥</div>
                <div style="font-weight:600;color:#374151">Belum ada vendor dengan status Fit to Work aktif.</div>
                <div style="font-size:13px;margin-top:6px">Vendor yang telah diperiksa dokter akan muncul di sini.</div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>

    
    <section id="patrol" class="section">
        <div class="section-inner">
            <div class="section-header">
                <h2 class="section-title">Patrol iZAT & Temuan Open</h2>
                <div class="section-line"></div>
            </div>

            
            <div class="patrol-temuan-grid">

                
                <div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($patrolPeriode && $patrolPeriode->jadwals->isNotEmpty()): ?>
                    <div class="patrol-card-main">
                        
                        <div class="patrol-card-header">
                            <div class="patrol-header-left">
                                <div class="patrol-icon">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="patrol-title">Jadwal Safety Patrol iZAT</div>
                                    <div class="patrol-subtitle"><?php echo e($patrolBulan); ?> <?php echo e($patrolTahun); ?></div>
                                </div>
                            </div>
                            <?php
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
                            ?>
                            <div class="patrol-count-badge">
                                <span class="patrol-count-num"><?php echo e($sudahLapor); ?>/<?php echo e($totalMingguIni); ?></span>
                                <span class="patrol-count-label">lapor</span>
                            </div>
                        </div>

                        
                        <div class="patrol-week-bar">
                            <span class="patrol-week-label">Petugas bertugas minggu ini</span>
                            <span class="patrol-week-range"><?php echo e($patrolMingguRange); ?></span>
                        </div>

                        
                        <div class="patrol-table-wrap" style="overflow-y:auto; max-height:280px;">
                            <table class="patrol-table">
                                <thead style="position:sticky; top:0; z-index:2;">
                                    <tr>
                                        <th class="col-no">NO</th>
                                        <th class="col-nama">NAMA PETUGAS</th>
                                        <th class="col-hari">HARI / TGL</th>
                                        <th class="col-status">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $jadwalMingguIni->values(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="<?php echo e($i % 2 === 0 ? 'row-even' : 'row-odd'); ?>">
                                        <td class="col-no"><?php echo e($i + 1); ?></td>
                                        <td class="col-nama">
                                            <?php echo e($jadwal->nama_petugas); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jadwal->lokasi_unit): ?>
                                            <div class="mt-1"><span class="unit-badge"><?php echo e($jadwal->lokasi_unit); ?></span></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="col-hari">
                                            <span class="hari-label"><?php echo e($jadwal->nama_hari); ?></span>
                                            <span class="tgl-label"><?php echo e($jadwal->tanggal_patrol->format('d/m')); ?></span>
                                        </td>
                                        <td class="col-status">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($jadwal->sudah_lapor): ?>
                                            <span class="chip-done">
                                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                Sudah
                                            </span>
                                            <?php else: ?>
                                            <span class="chip-pending">
                                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/></svg>
                                                Belum
                                            </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" style="text-align:center;padding:24px;color:#9ca3af;font-size:13px">
                                            Tidak ada jadwal patrol minggu ini.
                                        </td>
                                    </tr>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalMingguIni > 0): ?>
                        <div class="patrol-progress">
                            <span class="patrol-prog-label">Progress minggu ini</span>
                            <div class="patrol-prog-wrap">
                                <div class="patrol-prog-bar" style="width:<?php echo e(round(($sudahLapor / $totalMingguIni) * 100)); ?>%"></div>
                            </div>
                            <span class="patrol-prog-pct <?php echo e($sudahLapor === $totalMingguIni ? 'text-green' : 'text-gray'); ?>">
                                <?php echo e($sudahLapor); ?>/<?php echo e($totalMingguIni); ?> (<?php echo e(round(($sudahLapor / $totalMingguIni) * 100)); ?>%)
                            </span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        
                        <div class="patrol-footer">
                            <div class="patrol-salam">Semangat Pagi Power People — Salam Safety!</div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="patrol-card-main" style="padding:48px 20px;text-align:center">
                        <div style="font-size:3rem;margin-bottom:12px">📋</div>
                        <div style="font-weight:600;color:#374151">Tidak ada jadwal patrol untuk bulan ini</div>
                        <div style="color:#9ca3af;font-size:13px;margin-top:6px"><?php echo e($patrolBulan); ?> <?php echo e($patrolTahun); ?></div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
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

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($temuan_opens) && $temuan_opens->isNotEmpty()): ?>
                        <table class="temuan-table">
                            <thead>
                                <tr>
                                    <th class="no">NO</th>
                                    <th>BIDANG</th>
                                    <th class="jml">JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $temuan_opens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($i % 2 === 0 ? 'r-even' : 'r-odd'); ?>">
                                    <td class="no"><?php echo e($i + 1); ?></td>
                                    <td class="bidang"><?php echo e($t->bidang); ?></td>
                                    <td class="jml">
                                        <span class="jml-badge <?php echo e($t->jumlah_temuan > 10 ? 'danger' : ($t->jumlah_temuan > 5 ? 'warn' : 'ok')); ?>">
                                            <?php echo e($t->jumlah_temuan); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="text-align:right;font-size:11px;font-weight:700;padding:8px 12px;color:#374151">Total Temuan:</td>
                                    <td class="jml">
                                        <span class="jml-badge total"><?php echo e($temuan_opens->sum('jumlah_temuan')); ?></span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <?php else: ?>
                        <div style="padding:32px;text-align:center;color:#9ca3af;font-size:13px">
                            Tidak ada temuan open saat ini.
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    
    <footer class="footer">
        <div class="footer-inner">
            <div>
                <div class="footer-heading">D-SAVE</div>
                <div class="footer-text">
                    PT PLN Nusantara Power<br>
                    Unit Pembangkitan Sengkang<br>
                    
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
                <div class="footer-heading">Akses Cepat</div>
                <a href="/pegawai/apd" class="footer-link">Ambil / Pinjam APD</a>
                <a href="/pegawai/booking-klinik" class="footer-link">Booking Klinik</a>
                <a href="/vendor/registrasi" class="footer-link">Registrasi Gate Access</a>
                <a href="/fit-to-work" class="footer-link">Fit To Work</a>
            </div>
            <div>
                <div class="footer-heading">Darurat K3</div>
                <div class="footer-text">
                    Hubungi Tim K3 segera jika terjadi insiden atau keadaan darurat.<br><br>

                    
                    <strong style="color:#fbbf24">Email</strong> :
                    <a href="mailto:upsengkangk@gmail.com"
                       class="hover:text-[#FFC72C] transition-colors duration-200"
                       style="text-decoration: none; color: inherit;">
                       upsengkangk@gmail.com
                    </a><br>

                    
                    <strong style="color:#fbbf24">Telp</strong> :
                    <a href="tel:+6283878001602"
                       class="hover:text-[#FFC72C] transition-colors duration-200"
                       style="text-decoration: none; color: inherit;">
                       +6283878001602
                    </a><br>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            © <?php echo e(date('Y')); ?> PT PLN Nusantara Power – Unit Pembangkitan Sengkang. All rights reserved.
        </div>
    </footer>

    
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

    
    <div id="ftw-popup" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         onclick="if(event.target===this)closeFtwPopup()">
        <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">

            
            <div class="bg-[#003D7C] text-white p-5 rounded-t-2xl flex items-start justify-between">
                <div>
                    <div class="text-xs font-semibold px-2 py-1 rounded-full bg-white/20 inline-block mb-2">
                        🏥 Fit to Work
                    </div>
                    <div id="ftw-popup-nama" class="font-bold text-lg leading-tight">—</div>
                    <div id="ftw-popup-pekerjaan" class="text-blue-200 text-sm mt-1">—</div>
                </div>
                <button onclick="closeFtwPopup()"
                        class="text-white/70 hover:text-white text-2xl leading-none ml-4 flex-shrink-0">×</button>
            </div>

            <div class="p-5">

                
                <div class="bg-green-50 rounded-xl p-4 mb-5 flex items-center gap-3">
                    <span class="text-2xl">📅</span>
                    <div>
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wide mb-0.5">Durasi Pekerjaan</div>
                        <div class="font-bold text-gray-800">
                            <span id="ftw-popup-mulai">—</span> s/d <span id="ftw-popup-selesai">—</span>
                        </div>
                    </div>
                </div>

                
                <div>
                    <div class="font-bold text-gray-700 mb-3 flex items-center gap-2">
                        👷 Daftar Pekerja Tersertifikasi
                        <span id="ftw-popup-jumlah"
                              class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-semibold">0</span>
                    </div>

                    
                    <div class="bg-[#003D7C] text-white text-xs font-semibold rounded-t-xl overflow-hidden">
                        <div style="display:grid;grid-template-columns:40px 1fr 100px 100px">
                            <div class="px-3 py-2 text-center">No</div>
                            <div class="px-3 py-2">Nama Pekerja</div>
                            <div class="px-3 py-2 text-center">Kel.</div>
                            <div class="px-3 py-2 text-center">Status</div>
                        </div>
                    </div>

                    
                    <div id="ftw-popup-list"
                         class="border border-gray-200 rounded-b-xl overflow-hidden divide-y divide-gray-100">
                    </div>

                    <div id="ftw-popup-empty" class="hidden text-center py-8 text-gray-400 text-sm">
                        Belum ada pekerja tersertifikasi.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ── Mobile Menu & Header Logic ──
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

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 900) closeDrawer();
        });

        function checkScreen() {
            const isDesktop = window.innerWidth >= 900;
            const desktopNav = document.getElementById('desktop-nav');
            if (desktopNav) desktopNav.style.display = isDesktop ? 'flex' : 'none';
        }
        checkScreen();
        window.addEventListener('resize', checkScreen);

        // ── Swiper Banner (Library Swiper.js) ──
        new Swiper('.banner-swiper', {
            loop: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });

        // ── Tab Switcher Vendor ──
        function switchVendorTab(tab) {
            document.getElementById('vendor-tab-wpo').classList.toggle('hidden', tab !== 'wpo');
            document.getElementById('vendor-tab-gate').classList.toggle('hidden', tab !== 'gate');

            document.querySelectorAll('.vendor-tab-btn').forEach(btn => {
                btn.classList.remove('border-[#003D7C]','bg-[#003D7C]','text-white');
                btn.classList.add('border-gray-300','bg-white','text-gray-600');
            });

            const activeId = tab === 'wpo' ? 'tab-wpo-btn' : 'tab-gate-btn';
            const activeBtn = document.getElementById(activeId);
            if (activeBtn) {
                activeBtn.classList.remove('border-gray-300','bg-white','text-gray-600');
                activeBtn.classList.add('border-[#003D7C]','bg-[#003D7C]','text-white');
            }
        }

        // ── Vendor Popup Logic ──
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

        // ── Vendor Carousel Logic ──
        const vendorState = {
            wpo:  { current: 0, total: 0, perPage: 3 },
            gate: { current: 0, total: 0, perPage: 3 },
        };

        function getPerPage() {
            if (window.innerWidth <= 560) return 1;
            if (window.innerWidth <= 900) return 2;
            return 3;
        }

        function getArtikelPerPage() {
            if (window.innerWidth <= 560) return 2;
            if (window.innerWidth <= 900) return 2;
            return 4;
        }

        function initVendorCarousel(id) {
            const track = document.getElementById(id + '-swiper-track');
            if (!track) return;
            const slides = track.querySelectorAll('.swiper-slide');
            vendorState[id].total = slides.length;
            vendorState[id].current = 0;
            renderVendorCarousel(id);
            buildDots(id);
        }

        function renderVendorCarousel(id) {
            const track = document.getElementById(id + '-swiper-track');
            if (!track) return;
            const perPage = getPerPage();
            vendorState[id].perPage = perPage;
            const s = vendorState[id];

            const maxIndex = Math.max(0, s.total - perPage);
            s.current = Math.min(s.current, maxIndex);

            const slides = track.querySelectorAll('.swiper-slide');
            const gap = 16;
            const containerW = track.parentElement.offsetWidth;
            const slideW = (containerW - gap * (perPage - 1)) / perPage;

            slides.forEach(sl => {
                sl.style.width = slideW + 'px';
                sl.style.marginRight = gap + 'px';
            });

            const offset = s.current * (slideW + gap);
            track.style.transform = `translateX(-${offset}px)`;
            track.style.transition = 'transform .35s cubic-bezier(.4,0,.2,1)';

            updateDots(id);
            updateNavBtns(id);
        }

        function vendorSwipe(id, dir) {
            const s = vendorState[id];
            const maxIndex = Math.max(0, s.total - s.perPage);
            s.current = Math.max(0, Math.min(s.current + dir, maxIndex));
            renderVendorCarousel(id);
        }

        function buildDots(id) {
            const s = vendorState[id];
            const dotsEl = document.getElementById(id + '-dots');
            if (!dotsEl) return;
            const pages = Math.max(1, s.total - s.perPage + 1);
            dotsEl.innerHTML = Array.from({length: pages}, (_, i) =>
                `<div class="vendor-dot ${i === 0 ? 'active' : ''}" onclick="goToPage('${id}',${i})"></div>`
            ).join('');
        }

        function updateDots(id) {
            const s = vendorState[id];
            const dotsEl = document.getElementById(id + '-dots');
            if (!dotsEl) return;
            dotsEl.querySelectorAll('.vendor-dot').forEach((d, i) =>
                d.classList.toggle('active', i === s.current)
            );
        }

        function updateNavBtns(id) {
            const s = vendorState[id];
            const maxIndex = Math.max(0, s.total - s.perPage);
            const prev = document.getElementById(id + '-prev');
            const next = document.getElementById(id + '-next');
            if (prev) prev.disabled = s.current <= 0;
            if (next) next.disabled = s.current >= maxIndex;
        }

        function goToPage(id, page) {
            vendorState[id].current = page;
            renderVendorCarousel(id);
        }

        function toggleShowMore(id) {
            const grid   = document.getElementById(id + '-mobile-grid');
            const btn    = document.getElementById(id + '-show-more');
            const hidden = grid.querySelectorAll('.vendor-card-hidden');
            const isExpanded = btn.classList.contains('expanded');

            if (isExpanded) {
                hidden.forEach(c => c.style.display = 'none');
                btn.classList.remove('expanded');
                const count = grid.querySelectorAll('.vendor-card').length - 3;
                btn.innerHTML = `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg> Tampilkan ${count} vendor lainnya`;
            } else {
                hidden.forEach(c => c.style.display = 'block');
                btn.classList.add('expanded');
                btn.innerHTML = `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg> Sembunyikan`;
            }
        }

        // ── Artikel Carousel Logic ──
        const artikelState = { current: 0, total: 0, perPage: 3 };

        function initArtikelCarousel() {
            const track = document.getElementById('artikel-swiper-track');
            if (!track) return;
            artikelState.total = track.querySelectorAll('.swiper-slide').length;
            artikelState.current = 0;
            renderArtikelCarousel();
            buildArtikelDots();
        }

        function renderArtikelCarousel() {
            const track = document.getElementById('artikel-swiper-track');
            if (!track) return;
            const perPage = getArtikelPerPage();   // ← ubah ini
            artikelState.perPage = perPage;

            const maxIndex = Math.max(0, artikelState.total - perPage);
            artikelState.current = Math.min(artikelState.current, maxIndex);

            const slides = track.querySelectorAll('.swiper-slide');
            const gap = 20;
            const containerW = track.parentElement.offsetWidth;
            const slideW = (containerW - gap * (perPage - 1)) / perPage;

            slides.forEach(sl => {
                sl.style.width = slideW + 'px';
                sl.style.marginRight = gap + 'px';
            });

            track.style.transform = `translateX(-${artikelState.current * (slideW + gap)}px)`;
            track.style.transition = 'transform .35s cubic-bezier(.4,0,.2,1)';

            updateArtikelDots();
            updateArtikelNavBtns();
        }

        function artikelSwipe(dir) {
            const maxIndex = Math.max(0, artikelState.total - artikelState.perPage);
            artikelState.current = Math.max(0, Math.min(artikelState.current + dir, maxIndex));
            renderArtikelCarousel();
        }

        function buildArtikelDots() {
            const dotsEl = document.getElementById('artikel-dots');
            if (!dotsEl) return;
            const pages = Math.max(1, artikelState.total - artikelState.perPage + 1);
            dotsEl.innerHTML = Array.from({length: pages}, (_, i) =>
                `<div class="artikel-dot ${i === 0 ? 'active' : ''}" onclick="artikelGoToPage(${i})"></div>`
            ).join('');
        }

        function updateArtikelDots() {
            const dotsEl = document.getElementById('artikel-dots');
            if (!dotsEl) return;
            dotsEl.querySelectorAll('.artikel-dot').forEach((d, i) =>
                d.classList.toggle('active', i === artikelState.current)
            );
        }

        function updateArtikelNavBtns() {
            const maxIndex = Math.max(0, artikelState.total - artikelState.perPage);
            const prev = document.getElementById('artikel-prev');
            const next = document.getElementById('artikel-next');
            if (prev) prev.disabled = artikelState.current <= 0;
            if (next) next.disabled = artikelState.current >= maxIndex;
        }

        function artikelGoToPage(page) {
            artikelState.current = page;
            renderArtikelCarousel();
        }

        function toggleArtikelShowMore() {
            const grid   = document.getElementById('artikel-mobile-grid');
            const btn    = document.getElementById('artikel-show-more');
            const hidden = grid.querySelectorAll('.artikel-card-hidden');
            const isExpanded = btn.classList.contains('expanded');

            if (isExpanded) {
                hidden.forEach(c => c.style.display = 'none');
                btn.classList.remove('expanded');
                const count = grid.querySelectorAll('.article-card').length - 3;
                btn.innerHTML = `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg> Tampilkan ${count} artikel lainnya`;
            } else {
                hidden.forEach(c => c.style.display = 'flex');
                btn.classList.add('expanded');
                btn.innerHTML = `<svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg> Sembunyikan`;
            }
        }

        // ── Global Handlers (Load & Resize) ──
        window.addEventListener('load', () => {
            initVendorCarousel('wpo');
            initVendorCarousel('gate');
            initArtikelCarousel();
        });

        window.addEventListener('resize', () => {
            ['wpo', 'gate'].forEach(id => {
                buildDots(id);
                renderVendorCarousel(id);
            });
            buildArtikelDots();
            renderArtikelCarousel();
        });

        // ── Fit to Work Popup ──
        function openFtwPopup(data) {
            document.getElementById('ftw-popup-nama').textContent     = data.nama;
            document.getElementById('ftw-popup-pekerjaan').textContent = data.pekerjaan;
            document.getElementById('ftw-popup-mulai').textContent    = data.tanggal_mulai;
            document.getElementById('ftw-popup-selesai').textContent  = data.tanggal_selesai;

            const list     = document.getElementById('ftw-popup-list');
            const empty    = document.getElementById('ftw-popup-empty');
            const jmlBadge = document.getElementById('ftw-popup-jumlah');

            if (data.pekerjas && data.pekerjas.length > 0) {
                jmlBadge.textContent = data.pekerjas.length;
                empty.classList.add('hidden');
                list.classList.remove('hidden');
                list.innerHTML = data.pekerjas.map((p, i) => {
                    const statusLabel = p.status === 'fit'
                        ? '<span style="background:#dcfce7;color:#166534;font-size:11px;font-weight:700;padding:2px 8px;border-radius:999px">✅ Fit</span>'
                        : p.status === 'tidak_fit'
                            ? '<span style="background:#fee2e2;color:#991b1b;font-size:11px;font-weight:700;padding:2px 8px;border-radius:999px">❌ Tidak Fit</span>'
                            : '<span style="background:#fef9c3;color:#854d0e;font-size:11px;font-weight:700;padding:2px 8px;border-radius:999px">⏳ Menunggu</span>';
                    return `<div style="display:grid;grid-template-columns:40px 1fr 100px 110px;font-size:13px;background:${i % 2 === 1 ? '#f9fafb' : '#fff'}">
                        <div style="padding:10px 12px;text-align:center;color:#9ca3af;font-weight:600">${i + 1}.</div>
                        <div style="padding:10px 12px;font-weight:600;color:#111827">${p.nama}</div>
                        <div style="padding:10px 12px;text-align:center;color:#6b7280;font-size:12px">${p.jenis_kelamin}</div>
                        <div style="padding:10px 12px;text-align:center">${statusLabel}</div>
                    </div>`;
                }).join('');
            } else {
                jmlBadge.textContent = '0';
                list.innerHTML = '';
                empty.classList.remove('hidden');
            }

            document.getElementById('ftw-popup').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFtwPopup() {
            document.getElementById('ftw-popup').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/landing.blade.php ENDPATH**/ ?>