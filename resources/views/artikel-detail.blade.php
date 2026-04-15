{{-- ============================================================ --}}
{{-- FILE: resources/views/artikel-detail.blade.php              --}}
{{-- Halaman detail artikel K3                                   --}}
{{-- ============================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} – K3 PLN Sengkang</title>
    <meta name="description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Article prose styling */
        .prose-article { font-family: 'Lora', Georgia, serif; font-size: 1.075rem; line-height: 1.85; color: #374151; }
        .prose-article h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 700; color: #111827; margin: 2rem 0 0.75rem; }
        .prose-article h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.15rem; font-weight: 600; color: #1f2937; margin: 1.5rem 0 0.5rem; }
        .prose-article p { margin-bottom: 1.25rem; }
        .prose-article ul, .prose-article ol { padding-left: 1.5rem; margin-bottom: 1.25rem; }
        .prose-article li { margin-bottom: 0.4rem; }
        .prose-article strong { font-weight: 600; color: #111827; }
        .prose-article a { color: #1d4ed8; text-decoration: underline; }
        .prose-article blockquote { border-left: 4px solid #003D7C; padding: 1rem 1.5rem; background: #eff6ff; border-radius: 0 0.5rem 0.5rem 0; font-style: italic; margin: 1.5rem 0; }
        .prose-article img { max-width: 100%; border-radius: 0.75rem; margin: 1.5rem auto; display: block; }

        .category-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .3px;
        }
        .badge-kampanye { background: #fee2e2; color: #991b1b; }
        .badge-berita   { background: #dbeafe; color: #1e40af; }
        .badge-panduan  { background: #dcfce7; color: #166534; }
        .badge-lainnya  { background: #fef3c7; color: #92400e; }

        @media (max-width: 768px) {
            .prose-article { font-size: 1rem; }
        }
    </style>
</head>
<body class="bg-gray-50">

    {{-- Sticky Navbar --}}
    <nav class="bg-[#003D7C] text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/" class="text-blue-200 hover:text-white transition text-sm flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Beranda
                </a>
                <span class="text-blue-500 text-xs">/</span>
                <span class="text-blue-200 text-sm hidden sm:inline">Artikel K3</span>
            </div>
        </div>
    </nav>

    <article class="max-w-3xl mx-auto px-4 py-10">

        {{-- Category + meta --}}
        <div class="flex items-center gap-3 mb-5">
            <span class="category-badge badge-{{ $article->category }}">
                {{ ['kampanye'=>'Kampanye K3','berita'=>'Berita','panduan'=>'Panduan','lainnya'=>'Lainnya'][$article->category] ?? $article->category }}
            </span>
            @if($article->published_at)
            <span class="text-gray-400 text-sm">
                {{ $article->published_at->translatedFormat('d F Y') }}
            </span>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-6">
            {{ $article->title }}
        </h1>

        {{-- Thumbnail --}}
        @if($article->thumbnail)
        <div class="rounded-2xl overflow-hidden mb-8 aspect-video bg-gray-100">
            <img src="{{ Storage::url($article->thumbnail) }}"
                 alt="{{ $article->title }}"
                 class="w-full h-full object-cover">
        </div>
        @else
        <div class="rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200 mb-8 h-48 flex items-center justify-center">
            <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        @endif

        {{-- Divider --}}
        <div class="w-16 h-1 bg-yellow-400 rounded mb-8"></div>

        {{-- Article content --}}
        <div class="prose-article">
            {!! $article->content !!}
        </div>

        {{-- Share / back --}}
        <div class="mt-12 pt-8 border-t border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <a href="/#artikel"
               class="flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke semua artikel
            </a>

            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Bagikan:</span>
                <a href="https://wa.me/?text={{ urlencode($article->title . ' – ' . url()->current()) }}"
                   target="_blank"
                   class="flex items-center gap-1 bg-green-500 text-white px-3 py-1.5 rounded-lg hover:bg-green-600 transition text-xs font-semibold">
                    WhatsApp
                </a>
            </div>
        </div>

    </article>

    {{-- Related articles --}}
    @if($related->isNotEmpty())
    <section class="max-w-3xl mx-auto px-4 pb-16">
        <h2 class="text-xl font-bold text-gray-800 mb-5">Artikel Lainnya</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($related as $rel)
            <a href="{{ route('artikel.show', $rel->id) }}"
               class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                @if($rel->thumbnail)
                <img src="{{ Storage::url($rel->thumbnail) }}"
                     alt="{{ $rel->title }}"
                     class="w-full h-32 object-cover group-hover:scale-105 transition duration-300">
                @else
                <div class="w-full h-32 bg-gradient-to-br from-blue-100 to-blue-200"></div>
                @endif
                <div class="p-3">
                    <span class="text-xs text-blue-600 font-medium capitalize">{{ $rel->category }}</span>
                    <h3 class="text-sm font-semibold text-gray-800 mt-1 leading-snug line-clamp-2">{{ $rel->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Footer --}}
    <footer class="bg-[#003D7C] text-white py-6 mt-4">
        <div class="max-w-3xl mx-auto px-4 text-center text-blue-300 text-xs">
            © {{ date('Y') }} PT PLN Nusantara Power – Unit Pembangkitan Sengkang
        </div>
    </footer>

</body>
</html>
