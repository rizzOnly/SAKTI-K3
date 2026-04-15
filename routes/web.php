<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiFormController;
use App\Http\Controllers\VendorRegistrasiController;
use App\Models\{CmsBanner, CmsArticle, CmsVendor, CmsVendorFlow, VendorRegistrasi, PatrolPeriode, TemuanOpen};

Route::get('/', function () {
    // Cek expired otomatis untuk Gate Access
    VendorRegistrasi::where('status', 'aktif')
        ->where('tanggal_selesai', '<', today())
        ->update(['status' => 'expired']);

    $patrolPeriode = PatrolPeriode::where('bulan', now()->month)
        ->where('tahun', now()->year)
        ->where('is_active', true)
        ->with(['jadwals' => function($q) {
            // Jadwal minggu ini saja
            $q->whereBetween('tanggal_patrol', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->orderBy('tanggal_patrol');
        }])
        ->first();

    return view('landing', [
        'banners'           => CmsBanner::where('is_active', true)->orderBy('urutan')->get(),
        'articles'          => CmsArticle::where('is_published', true)->orderByDesc('published_at')->take(6)->get(),
        'vendorsWpo'        => CmsVendor::where('is_active', true)->orderBy('nama_vendor')->get(),
        'vendorsGate'       => VendorRegistrasi::aktifDanBerlaku()->with(['pekerjasLulus'])->get(),
        'flows'             => CmsVendorFlow::all(),

'temuan_opens' => \App\Models\TemuanOpen::aktif()->get(),
        // Di array view:
'patrolPeriode'  => $patrolPeriode,
'patrolBulan'    => PatrolPeriode::namaBulan(now()->month),
'patrolTahun'    => now()->year,
'patrolMingguRange' => now()->startOfWeek()->format('d') . '–' .
                       now()->endOfWeek()->format('d M Y'),
    ]);
});
// ─── Form Publik Pegawai (tanpa autentikasi Filament) ──────────
Route::prefix('pegawai')->name('pegawai.')->group(function () {
    // Halaman gabungan APD (ambil + pinjam)
    Route::get('/apd', [PegawaiFormController::class, 'showApd'])->name('apd');
    Route::post('/apd/ambil', [PegawaiFormController::class, 'storeAmbil'])->name('apd.ambil.store');
    Route::post('/apd/pinjam', [PegawaiFormController::class, 'storePinjam'])->name('apd.pinjam.store');

    // Booking klinik (tidak berubah)
    Route::get('/booking-klinik', [PegawaiFormController::class, 'showBooking'])->name('booking');
    Route::post('/booking-klinik', [PegawaiFormController::class, 'storeBooking'])->name('booking.store');

    // API
    Route::get('/api/slots', [PegawaiFormController::class, 'getSlots'])->name('api.slots');
    Route::get('/api/cek-nip', [PegawaiFormController::class, 'cekNip'])->name('api.cek-nip');
});

// ─── Route artikel detail (named route) ────────────────────────
Route::get('/artikel/{id}', function ($id) {
    $article = CmsArticle::where('is_published', true)->findOrFail($id);

    $related = CmsArticle::where('is_published', true)
        ->where('id', '!=', $id)
        ->where('category', $article->category)
        ->latest('published_at')
        ->take(3)
        ->get();

    // Kalau kurang dari 3, tambah dari kategori lain
    if ($related->count() < 3) {
        $extra = CmsArticle::where('is_published', true)
            ->where('id', '!=', $id)
            ->whereNotIn('id', $related->pluck('id'))
            ->latest('published_at')
            ->take(3 - $related->count())
            ->get();
        $related = $related->merge($extra);
    }

    return view('artikel-detail', compact('article', 'related'));
})->name('artikel.show');

// ─── Form & Survey Vendor ──────────────────────────────────────
Route::prefix('vendor')->name('vendor.')->group(function () {
    // Registrasi publik
    Route::get('/registrasi',  [VendorRegistrasiController::class, 'showRegistrasi'])->name('registrasi');
    Route::post('/registrasi', [VendorRegistrasiController::class, 'storeRegistrasi'])->name('registrasi.store');

    // Survey per token registrasi
    Route::get('/survey/preview',    [VendorRegistrasiController::class, 'previewSurvey'])->name('survey.preview');
    Route::get('/survey/{token}',    [VendorRegistrasiController::class, 'showSurvey'])->name('survey');
    Route::post('/survey/{token}',   [VendorRegistrasiController::class, 'submitSurvey'])->name('survey.submit');
});
