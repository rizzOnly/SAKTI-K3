<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Artikel K3 – D-SAVE</title>
    <link rel="icon" href="<?php echo e(asset('images/logo-sakti.png')); ?>" type="image/png">
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
        .article-card:hover {
            box-shadow: 0 8px 32px rgba(0,0,0,.12);
            transform: translateY(-3px);
        }
        .article-img-wrap {
            position: relative;
            width: 100%;
            aspect-ratio: 905 / 1280;
            overflow: hidden;
            background: #ffffff;
        }
        .article-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            z-index: 1;
            transition: transform .3s;
        }
        .article-card:hover .article-img { transform: scale(1.04); }
        .article-img-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        }
        .article-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
        .article-cat {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: capitalize;
            align-self: flex-start;
        }
        .cat-kampanye { background: #fee2e2; color: #991b1b; }
        .cat-berita   { background: #dbeafe; color: #1e40af; }
        .cat-panduan  { background: #dcfce7; color: #166534; }
        .cat-lainnya  { background: #fef3c7; color: #92400e; }
        .article-title {
            font-weight: 700;
            color: #111827;
            font-size: 15px;
            line-height: 1.45;
            margin: 0 0 6px;
        }
        /* TAMBAHAN: Style untuk cuplikan isi artikel */
        .article-excerpt {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
            margin: 0 0 12px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2; /* Batasi maksimal 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex: 1; /* Mendorong tanggal ke paling bawah */
        }
        .article-date { color: #9ca3af; font-size: 12px; margin-top: auto; }

        /* ── Grid (DIPERBARUI MENJADI 4 KOLOM AGAR LEBIH KECIL) ── */
        .artikel-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* ← Diubah dari 3 ke 4 */
            gap: 20px;
            margin-bottom: 40px;
        }
        @media (max-width: 1024px) { .artikel-grid { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px)  { .artikel-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px)  { .artikel-grid { grid-template-columns: 1fr; } }

        /* ── Filter Form ── */
        .filter-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            margin-bottom: 28px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
        }
        .filter-group { display: flex; flex-direction: column; gap: 6px; }
        .filter-label {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .filter-input {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: #111827;
            outline: none;
            transition: border-color .2s;
            appearance: none;
        }
        .filter-input:focus { border-color: var(--pln-blue); }
        .filter-input-search { padding-left: 38px; }
        .search-wrap { position: relative; }
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
        .btn-cari {
            background: var(--pln-blue);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            transition: background .2s;
            white-space: nowrap;
        }
        .btn-cari:hover { background: #002d5c; }
        .btn-reset {
            background: #f1f5f9;
            color: #374151;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
        }
        .btn-reset:hover { border-color: var(--pln-blue); color: var(--pln-blue); }

        /* ── Filter badge aktif ── */
        .filter-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        /* ── Pagination ── */
        .page-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid #e5e7eb;
            background: #fff;
            color: #374151;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }
        .page-btn:hover { border-color: var(--pln-blue); color: var(--pln-blue); }
        .page-btn.active { background: var(--pln-blue); border-color: var(--pln-blue); color: #fff; }
        .page-btn.disabled { opacity: .4; pointer-events: none; cursor: default; }
    </style>
</head>
<body>

    
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
                <img src="<?php echo e(asset('images/logo-sakti.png')); ?>" alt="Logo D-SAVE" style="height:36px;width:auto;object-fit:contain">
            </a>
        </div>
    </nav>

    <div style="max-width:1200px;margin:0 auto;padding:32px 16px 64px">

        
        <div style="margin-bottom:28px">
            <h1 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#111827;margin:0 0 6px;display:flex;align-items:center;gap:10px">
                <span>📰</span> Semua Artikel K3
            </h1>
            <p style="color:#6b7280;font-size:14px;margin:0">
                Total <strong><?php echo e($articles->total()); ?></strong> artikel tersedia
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q') || request('kategori') || request('tahun') || request('bulan')): ?>
                    — <span style="color:#d97706">filter aktif</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        </div>

        
        <form method="GET" action="<?php echo e(route('artikel.index')); ?>" id="filter-form">
            <div class="filter-card">

                
                <div class="filter-group" style="flex:1;min-width:200px">
                    <label class="filter-label">Cari Judul</label>
                    <div class="search-wrap">
                        <svg class="search-icon" width="16" height="16" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="search" name="q" value="<?php echo e(request('q')); ?>"
                               placeholder="Ketik judul artikel..."
                               class="filter-input filter-input-search"
                               style="width:100%"
                               id="search-input">
                    </div>
                </div>

                
                <div class="filter-group" style="min-width:170px">
                    <label class="filter-label">Kategori</label>
                    <select name="kategori" class="filter-input" onchange="this.form.submit()" style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;padding-right:36px;cursor:pointer">
                        <option value="">Semua Kategori</option>
                        <option value="kampanye" <?php echo e(request('kategori') === 'kampanye' ? 'selected' : ''); ?>>🔴 Kampanye K3</option>
                        <option value="berita"   <?php echo e(request('kategori') === 'berita'   ? 'selected' : ''); ?>>🔵 Berita</option>
                        <option value="panduan"  <?php echo e(request('kategori') === 'panduan'  ? 'selected' : ''); ?>>🟢 Panduan</option>
                        <option value="lainnya"  <?php echo e(request('kategori') === 'lainnya'  ? 'selected' : ''); ?>>🟡 Lainnya</option>
                    </select>
                </div>

                
                <div class="filter-group" style="min-width:130px">
                    <label class="filter-label">Tahun</label>
                    <select name="tahun" class="filter-input" onchange="this.form.submit()" style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;padding-right:36px;cursor:pointer">
                        <option value="">Semua Tahun</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tahunList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tahun); ?>" <?php echo e(request('tahun') == $tahun ? 'selected' : ''); ?>><?php echo e($tahun); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                
                <div class="filter-group" style="min-width:150px">
                    <label class="filter-label">Bulan</label>
                    <select name="bulan" class="filter-input" onchange="this.form.submit()" style="background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;padding-right:36px;cursor:pointer">
                        <option value="">Semua Bulan</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $bln): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($i+1); ?>" <?php echo e(request('bulan') == $i+1 ? 'selected' : ''); ?>><?php echo e($bln); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                
                <div style="display:flex;gap:8px;align-items:flex-end">
                    <button type="submit" class="btn-cari">Cari</button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q') || request('kategori') || request('tahun') || request('bulan')): ?>
                    <a href="<?php echo e(route('artikel.index')); ?>" class="btn-reset">✕ Reset</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </form>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('kategori') || request('tahun') || request('bulan')): ?>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;align-items:center">
            <span style="font-size:12px;color:#6b7280;font-weight:600">Filter aktif:</span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('kategori')): ?>
            <span class="filter-badge" style="background:#dbeafe;color:#1e40af">
                Kategori: <?php echo e(ucfirst(request('kategori'))); ?>

            </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('tahun')): ?>
            <span class="filter-badge" style="background:#dcfce7;color:#166534">
                Tahun: <?php echo e(request('tahun')); ?>

            </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('bulan')): ?>
            <?php $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; ?>
            <span class="filter-badge" style="background:#fef3c7;color:#92400e">
                Bulan: <?php echo e($namaBulan[request('bulan')] ?? ''); ?>

            </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articles->isNotEmpty()): ?>

        <div class="artikel-grid">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('artikel.show', $article->id)); ?>" class="article-card">

                
                <div class="article-img-wrap">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->thumbnail): ?>
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
                    <span class="article-cat cat-<?php echo e($article->category); ?>">
                        <?php echo e(['kampanye'=>'Kampanye K3','berita'=>'Berita','panduan'=>'Panduan','lainnya'=>'Lainnya'][$article->category] ?? $article->category); ?>

                    </span>
                    <h3 class="article-title"><?php echo e($article->title); ?></h3>

                    
                    <p class="article-excerpt">
                        <?php echo e(\Illuminate\Support\Str::limit(strip_tags($article->content ?? $article->body ?? ''), 90)); ?>

                    </p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($article->published_at): ?>
                    <div class="article-date"><?php echo e($article->published_at->translatedFormat('d M Y')); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articles->hasPages()): ?>
        <div style="display:flex;align-items:center;justify-content:center;gap:6px;flex-wrap:wrap;margin-top:8px">

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articles->onFirstPage()): ?>
            <span class="page-btn disabled">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
            <?php else: ?>
            <a href="<?php echo e($articles->previousPageUrl()); ?>" class="page-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $articles->getUrlRange(max(1, $articles->currentPage()-2), min($articles->lastPage(), $articles->currentPage()+2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($url); ?>" class="page-btn <?php echo e($page === $articles->currentPage() ? 'active' : ''); ?>">
                <?php echo e($page); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($articles->hasMorePages()): ?>
            <a href="<?php echo e($articles->nextPageUrl()); ?>" class="page-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <?php else: ?>
            <span class="page-btn disabled">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <span style="font-size:13px;color:#6b7280;margin-left:8px">
                Halaman <?php echo e($articles->currentPage()); ?> dari <?php echo e($articles->lastPage()); ?>

            </span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php else: ?>
        
        <div style="text-align:center;padding:80px 20px">
            <div style="font-size:4rem;margin-bottom:16px">📭</div>
            <div style="font-weight:700;color:#374151;font-size:1.2rem;margin-bottom:8px">
                Artikel tidak ditemukan
            </div>
            <div style="color:#9ca3af;font-size:14px;margin-bottom:24px">
                Coba ubah kata kunci atau hapus filter yang aktif.
            </div>
            <a href="<?php echo e(route('artikel.index')); ?>"
               style="display:inline-block;background:var(--pln-blue);color:#fff;padding:12px 28px;border-radius:12px;font-weight:700;font-size:14px;text-decoration:none">
                Lihat Semua Artikel
            </a>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>

    
    <footer style="background:var(--pln-blue);color:#fff;padding:24px 16px;text-align:center">
        <div style="color:#4b7ab5;font-size:12px">
            © <?php echo e(date('Y')); ?> PT PLN Nusantara Power – Unit Pembangkitan Sengkang. All rights reserved.
        </div>
    </footer>

    <script>
        // Auto-submit search dengan debounce 500ms
        const searchInput = document.getElementById('search-input');
        let debounceTimer;
        searchInput?.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500);
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\k3-pltgu\resources\views/artikel-index.blade.php ENDPATH**/ ?>