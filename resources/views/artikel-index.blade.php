<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Artikel K3 – D-SAVE</title>
    <link rel="icon" href="{{ asset('images/logo-sakti.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --pln-blue: #003D7C; --pln-yellow: #FFC72C; }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; background: #f8fafc; }

        /* ── Navbar ── */
        .navbar { background: var(--pln-blue); position: sticky; top: 0; z-index: 50; box-shadow: 0 2px 20px rgba(0,0,0,.25); }
        .nav-inner { max-width: 1200px; margin: 0 auto; padding: 0 1rem; display: flex; align-items: center; justify-content: space-between; height: 64px; }

        /* ── Article Card ── */
        .article-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            transition: all .25s;
            text-decoration: none;
            display: flex;
            flex-direction: column;
        }
        .article-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,.12); transform: translateY(-3px); }
        .article-img-wrap { position: relative; width: 100%; aspect-ratio: 905 / 1280; overflow: hidden; background: #ffffff; }
        .article-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain; z-index: 1; transition: transform .3s; }
        .article-card:hover .article-img { transform: scale(1.04); }
        .article-img-placeholder { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
        .article-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
        .article-cat {
            display: inline-block; padding: 3px 10px; border-radius: 9999px;
            font-size: 11px; font-weight: 600; margin-bottom: 8px;
            text-transform: capitalize; align-self: flex-start;
        }
        .cat-kampanye { background: #fee2e2; color: #991b1b; }
        .cat-berita   { background: #dbeafe; color: #1e40af; }
        .cat-panduan  { background: #dcfce7; color: #166534; }
        .cat-lainnya  { background: #fef3c7; color: #92400e; }
        .article-title { font-weight: 700; color: #111827; font-size: 15px; line-height: 1.45; margin: 0 0 6px; }
        .article-excerpt {
            color: #6b7280; font-size: 13px; line-height: 1.5; margin: 0 0 12px 0;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; flex: 1;
        }
        .article-date { color: #9ca3af; font-size: 12px; margin-top: auto; }

        /* ── Grid: 4 Kolom di PC, 2 Kolom di HP ── */
        .artikel-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px; }
        @media (max-width: 1024px) { .artikel-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px)  { .artikel-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; } }
        @media (max-width: 480px)  {
            .artikel-grid {
                grid-template-columns: repeat(2, 1fr); /* MEMAKSA 2 KOLOM DI HP */
                gap: 10px; /* Jarak diperkecil agar tidak sesak */
            }
            /* Menyesuaikan ukuran text di HP agar muat di 2 kolom */
            .article-body { padding: 10px; }
            .article-cat { font-size: 9px; padding: 2px 8px; margin-bottom: 6px; }
            .article-title { font-size: 12px; line-height: 1.35; margin: 0 0 4px; }
            .article-excerpt { font-size: 10px; line-height: 1.4; margin: 0 0 8px 0; -webkit-line-clamp: 2; }
            .article-date { font-size: 10px; }
        }

        /* ── Sleek Filter Bar ── */
        .filter-card {
            background: #fff; border-radius: 12px; padding: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.05); margin-bottom: 24px;
            display: flex; flex-wrap: nowrap; gap: 10px; align-items: center;
        }
        .search-wrap { flex: 2; position: relative; min-width: 150px; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; }
        .filter-input {
            border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px;
            font-size: 13px; background: #f9fafb; color: #111827; outline: none;
            transition: all .2s; height: 42px; width: 100%; appearance: none; font-family: inherit;
        }
        .filter-input:focus { border-color: var(--pln-blue); background: #fff; box-shadow: 0 0 0 3px rgba(0,61,124,0.1); }
        .filter-input-search { padding-left: 36px; }
        .filter-select {
            flex: 1; min-width: 120px; cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 10px center; background-size: 16px; padding-right: 30px;
        }
        .btn-cari {
            background: var(--pln-blue); color: #fff; border: none; border-radius: 8px;
            padding: 0 20px; height: 42px; font-size: 13px; font-weight: 700; font-family: inherit;
            cursor: pointer; transition: background .2s; white-space: nowrap; flex-shrink: 0;
        }
        .btn-cari:hover { background: #002d5c; }
        .btn-reset {
            background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 8px;
            width: 42px; height: 42px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .2s; text-decoration: none; flex-shrink: 0;
        }
        .btn-reset:hover { background: #fca5a5; color: #991b1b; }

        /* Filter Responsive HP */
        @media (max-width: 800px) {
            .filter-card { flex-wrap: wrap; padding: 12px; }
            .search-wrap { flex: 100%; width: 100%; }
            .filter-select { flex: 1; min-width: 30%; padding: 10px 8px; font-size: 12px; background-position: right 6px center; }
            .btn-cari { flex: 1; }
        }
        @media (max-width: 480px) {
             .filter-select { min-width: 45%; }
        }

        /* ── Filter badge aktif ── */
        .filter-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; }

        /* ── Pagination ── */
        .page-btn {
            display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px;
            font-size: 14px; font-weight: 600; border: 2px solid #e5e7eb; background: #fff; color: #374151;
            text-decoration: none; transition: all .2s; cursor: pointer;
        }
        .page-btn:hover { border-color: var(--pln-blue); color: var(--pln-blue); }
        .page-btn.active { background: var(--pln-blue); border-color: var(--pln-blue); color: #fff; }
        .page-btn.disabled { opacity: .4; pointer-events: none; cursor: default; }
    </style>
</head>
<body>

    {{-- ── NAVBAR ── --}}
    <nav class="navbar">
        <div class="nav-inner">
            <div style="display:flex;align-items:center;gap:12px">
                <a href="/"
                   style="color:#93c5fd;font-size:14px;text-decoration:none;display:flex;align-items:center;gap:6px;transition:color .2s"
                   onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#93c5fd'">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Beranda
                </a>
                <span style="color:#3b6daa;font-size:12px">/</span>
                <span style="color:#fff;font-weight:700;font-size:15px">Semua Artikel K3</span>
            </div>
            <a href="/" style="display:flex;align-items:center;gap:8px;text-decoration:none">
                <img src="{{ asset('images/logo-sakti.png') }}" alt="Logo D-SAVE" style="height:36px;width:auto;object-fit:contain">
            </a>
        </div>
    </nav>

    <div style="max-width:1200px;margin:0 auto;padding:32px 16px 64px">

        {{-- ── Header ── --}}
        <div style="margin-bottom:24px">
            <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#111827;margin:0 0 6px;display:flex;align-items:center;gap:10px">
                <span>📰</span> Semua Artikel K3
            </h1>
            <p style="color:#6b7280;font-size:14px;margin:0">
                Total <strong>{{ $articles->total() }}</strong> artikel tersedia
            </p>
        </div>

        {{-- ── ONE-LINE SLEEK FILTER BAR ── --}}
        <form method="GET" action="{{ route('artikel.index') }}" id="filter-form">
            <div class="filter-card">

                {{-- Search --}}
                <div class="search-wrap">
                    <svg class="search-icon" width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Ketik judul artikel..." class="filter-input filter-input-search" id="search-input">
                </div>

                {{-- Kategori --}}
                <select name="kategori" class="filter-input filter-select" onchange="this.form.submit()">
                    <option value="">Kategori</option>
                    <option value="kampanye" {{ request('kategori') === 'kampanye' ? 'selected' : '' }}>🔴 Kampanye</option>
                    <option value="berita"   {{ request('kategori') === 'berita'   ? 'selected' : '' }}>🔵 Berita</option>
                    <option value="panduan"  {{ request('kategori') === 'panduan'  ? 'selected' : '' }}>🟢 Panduan</option>
                    <option value="lainnya"  {{ request('kategori') === 'lainnya'  ? 'selected' : '' }}>🟡 Lainnya</option>
                </select>

                {{-- Tahun --}}
                <select name="tahun" class="filter-input filter-select" onchange="this.form.submit()">
                    <option value="">Tahun</option>
                    @foreach($tahunList as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>

                {{-- Bulan --}}
                <select name="bulan" class="filter-input filter-select" onchange="this.form.submit()">
                    <option value="">Bulan</option>
                    @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $bln)
                    <option value="{{ $i+1 }}" {{ request('bulan') == $i+1 ? 'selected' : '' }}>{{ $bln }}</option>
                    @endforeach
                </select>

                {{-- Buttons --}}
                <button type="submit" class="btn-cari">Cari</button>
                @if(request('q') || request('kategori') || request('tahun') || request('bulan'))
                <a href="{{ route('artikel.index') }}" class="btn-reset" title="Reset Filter">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
                @endif
            </div>
        </form>

        {{-- ── Filter aktif badges ── --}}
        @if(request('kategori') || request('tahun') || request('bulan'))
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;align-items:center">
            <span style="font-size:12px;color:#6b7280;font-weight:600">Filter aktif:</span>
            @if(request('kategori'))
            <span class="filter-badge" style="background:#dbeafe;color:#1e40af">Kategori: {{ ucfirst(request('kategori')) }}</span>
            @endif
            @if(request('tahun'))
            <span class="filter-badge" style="background:#dcfce7;color:#166534">Tahun: {{ request('tahun') }}</span>
            @endif
            @if(request('bulan'))
            @php $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
            <span class="filter-badge" style="background:#fef3c7;color:#92400e">Bulan: {{ $namaBulan[request('bulan')] ?? '' }}</span>
            @endif
        </div>
        @endif

        {{-- ── Grid Artikel ── --}}
        @if($articles->isNotEmpty())

        <div class="artikel-grid">
            @foreach($articles as $article)
            <a href="{{ route('artikel.show', $article->id) }}" class="article-card">
                <div class="article-img-wrap">
                    @if($article->thumbnail)
                    <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="article-img" loading="lazy">
                    @else
                    <div class="article-img-placeholder">
                        <svg width="48" height="48" fill="none" stroke="#93c5fd" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @endif
                </div>

                <div class="article-body">
                    <span class="article-cat cat-{{ $article->category }}">
                        {{ ['kampanye'=>'Kampanye','berita'=>'Berita','panduan'=>'Panduan','lainnya'=>'Lainnya'][$article->category] ?? $article->category }}
                    </span>
                    <h3 class="article-title">{{ $article->title }}</h3>
                    <p class="article-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($article->content ?? $article->body ?? ''), 90) }}</p>

                    @if($article->published_at)
                    <div class="article-date">{{ $article->published_at->translatedFormat('d M Y') }}</div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        {{-- ── Pagination ── --}}
        @if($articles->hasPages())
        <div style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;margin-top:8px">
            {{-- Prev --}}
            @if($articles->onFirstPage())
            <span class="page-btn disabled"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></span>
            @else
            <a href="{{ $articles->previousPageUrl() }}" class="page-btn"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            @endif

            {{-- Page numbers ── --}}
            @foreach($articles->getUrlRange(max(1, $articles->currentPage()-2), min($articles->lastPage(), $articles->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="page-btn {{ $page === $articles->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach

            {{-- Next --}}
            @if($articles->hasMorePages())
            <a href="{{ $articles->nextPageUrl() }}" class="page-btn"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
            @else
            <span class="page-btn disabled"><svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></span>
            @endif

            <span style="font-size:13px;color:#6b7280;margin-left:8px">Halaman {{ $articles->currentPage() }} dari {{ $articles->lastPage() }}</span>
        </div>
        @endif

        @else
        <div style="text-align:center;padding:80px 20px">
            <div style="font-size:4rem;margin-bottom:16px">📭</div>
            <div style="font-weight:700;color:#374151;font-size:1.2rem;margin-bottom:8px">Artikel tidak ditemukan</div>
            <div style="color:#9ca3af;font-size:14px;margin-bottom:24px">Coba ubah kata kunci atau hapus filter yang aktif.</div>
            <a href="{{ route('artikel.index') }}" style="display:inline-block;background:var(--pln-blue);color:#fff;padding:12px 28px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none">Lihat Semua Artikel</a>
        </div>
        @endif

    </div>

    {{-- ── Footer ── --}}
    <footer style="background:var(--pln-blue);color:#fff;padding:24px 16px;text-align:center">
        <div style="color:#4b7ab5;font-size:12px">© {{ date('Y') }} PT PLN Nusantara Power – Unit Pembangkitan Sengkang. All rights reserved.</div>
    </footer>

    <script>
        // Auto-submit search dengan debounce 500ms
        const searchInput = document.getElementById('search-input');
        let debounceTimer;
        searchInput?.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => { document.getElementById('filter-form').submit(); }, 500);
        });
    </script>
</body>
</html>
