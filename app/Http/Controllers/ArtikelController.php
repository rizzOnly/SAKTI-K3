<?php
namespace App\Http\Controllers;

use App\Models\CmsArticle;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $query = CmsArticle::where('is_published', true);

        // Filter pencarian judul
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        // Filter tahun
        if ($request->filled('tahun')) {
            $query->whereYear('published_at', $request->tahun);
        }

        // Filter bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('published_at', $request->bulan);
        }

        $articles = $query->orderByDesc('published_at')->paginate(12)->withQueryString();

        // Ambil daftar tahun yang ada untuk dropdown filter
        $tahunList = CmsArticle::where('is_published', true)
            ->selectRaw('YEAR(published_at) as tahun')
            ->whereNotNull('published_at')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        return view('artikel-index', compact('articles', 'tahunList'));
    }
}
