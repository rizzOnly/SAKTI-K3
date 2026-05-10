<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use App\Models\User;
use App\Models\VendorPekerja;
use App\Models\VendorRegistrasi;
use App\Models\CmsVendor;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VendorRegistrasiController extends Controller
{
    // ─── STEP 1: Form Registrasi ────────────────────────────

    public function showRegistrasi()
    {
        // Ambil vendor WPO PLUS yang aktif dan masih berlaku
        $vendorsWpo = \App\Models\CmsVendor::where('is_active', true)
            ->whereNotNull('pekerja_json')
            ->get();

        return view('vendor.form-registrasi', compact('vendorsWpo'));
    }

    public function storeRegistrasi(Request $request)
    {
        $data = $request->validate([
            'cms_vendor_id'   => 'required|exists:cms_vendors,id',
            'pekerja_nama'    => 'required|string|max:100',
            'no_wa_pic'       => 'nullable|string|max:20',
            'email_pic'       => 'nullable|email|max:255',
        ]);

        if (empty($data['no_wa_pic']) && empty($data['email_pic'])) {
            return back()->withInput()
                ->withErrors(['kontak' => 'Wajib isi minimal salah satu kontak (WA atau email) PIC.']);
        }

        // ─────────────────────────────────────────────────────
        // INTI PERBAIKAN:
        // Cari apakah vendor WPO ini SUDAH punya registrasi gate
        // yang masih aktif. Kalau sudah ada → pakai yang lama.
        // Kalau belum ada → buat baru.
        // ─────────────────────────────────────────────────────
        $registrasi = VendorRegistrasi::where('cms_vendor_id', $data['cms_vendor_id'])
            ->where('status', 'aktif')
            ->first();

        if ($registrasi) {
            // ── Sudah ada: cek apakah nama pekerja ini sudah terdaftar ──
            $sudahAda = $registrasi->pekerjas()
                ->where('nama_pekerja', $data['pekerja_nama'])
                ->exists();

            if ($sudahAda) {
                // Pekerja ini sudah pernah daftar — langsung ke survey
                $pekerja = $registrasi->pekerjas()
                    ->where('nama_pekerja', $data['pekerja_nama'])
                    ->first();

                if ($pekerja->survey_lulus) {
                    // Sudah lulus survey, redirect kembali dengan info
                    return redirect()->route('vendor.survey', $registrasi->token_registrasi)
                        ->with('survey_result', [
                            'lulus'        => true,
                            'skor'         => $pekerja->survey_skor ?? 100,
                            'nama_pekerja' => $pekerja->nama_pekerja,
                        ]);
                }

                // Belum lulus → ke halaman survey untuk mengulang
                return redirect()->route('vendor.survey', $registrasi->token_registrasi);
            }

            // ── Pekerja baru dari vendor yang sama → tambahkan saja ──
            VendorPekerja::create([
                'vendor_registrasi_id' => $registrasi->id,
                'nama_pekerja'         => $data['pekerja_nama'],
                'survey_lulus'         => false,
            ]);

        } else {
            // ── Belum ada registrasi → buat baru berdasarkan WPO ──
            $vendor = \App\Models\CmsVendor::findOrFail($data['cms_vendor_id']);

            $registrasi = VendorRegistrasi::create([
                'cms_vendor_id'   => $vendor->id,
                'nama_perusahaan' => $vendor->nama_vendor,
                'nama_pekerjaan'  => $vendor->nama_pekerjaan,
                'tanggal_mulai'   => $vendor->tanggal_mulai,
                'tanggal_selesai' => $vendor->tanggal_selesai,
                'no_wa_pic'       => $data['no_wa_pic'],
                'email_pic'       => $data['email_pic'],
                'status'          => 'aktif',
                'is_active'       => true,
            ]);

            VendorPekerja::create([
                'vendor_registrasi_id' => $registrasi->id,
                'nama_pekerja'         => $data['pekerja_nama'],
                'survey_lulus'         => false,
            ]);
        }

        // Notif admin
        $admins = \App\Models\User::role('admin_k3')->whereNotNull('no_hp')->get();
        foreach ($admins as $admin) {
            \App\Services\WhatsAppService::send($admin->no_hp,
                "🏢 *Registrasi Gate Access Baru*\n".
                "Perusahaan: {$registrasi->nama_perusahaan}\n".
                "Pekerja: {$data['pekerja_nama']}\n".
                "Survey link: {$registrasi->survey_url}"
            );
        }

        return redirect()->route('vendor.survey', $registrasi->token_registrasi)
            ->with('registrasi_baru', true);
    }

    // ─── STEP 2: Halaman Survey ─────────────────────────────

    public function showSurvey(string $token)
    {
        $registrasi = VendorRegistrasi::where('token_registrasi', $token)
            ->where('status', 'aktif')
            ->firstOrFail();

        if ($registrasi->tanggal_selesai < today()) {
            return view('vendor.survey-expired', compact('registrasi'));
        }

        $pekerjas = $registrasi->pekerjas()->orderBy('id')->get();
        $questions = SurveyQuestion::aktif()->with('options')->get();

        if ($questions->isEmpty()) {
            return view('vendor.survey-kosong');
        }

        $questionsShuffled = $questions->shuffle()->values();
        $questionsShuffled->each(function ($q) {
            $q->options = $q->options->shuffle()->values();
        });

        session(['survey_order' => $questionsShuffled->pluck('id')->toArray(),
            'survey_options_order' => $questionsShuffled->mapWithKeys(function ($q) {
                return [$q->id => $q->options->pluck('id')->toArray()];
            })->toArray()]);

        $questions = $questionsShuffled;

        return view('vendor.survey', compact('registrasi', 'pekerjas', 'questions'));
    }

    public function previewSurvey()
    {
        $questions = SurveyQuestion::aktif()->with('options')->get();
        $questions = $questions->shuffle()->values();
        $questions->each(function ($q) {
            $q->options = $q->options->shuffle()->values();
        });

        return view('vendor.survey-preview', compact('questions'));
    }

    // ─── STEP 3: Submit Survey ──────────────────────────────

    public function submitSurvey(Request $request, string $token)
    {
        $registrasi = VendorRegistrasi::where('token_registrasi', $token)
            ->where('status', 'aktif')
            ->firstOrFail();

        $request->validate([
            'pekerja_id' => 'required|exists:vendor_pekerjas,id',
            'jawaban'    => 'required|array',
        ]);

        $pekerja = VendorPekerja::where('id', $request->pekerja_id)
            ->where('vendor_registrasi_id', $registrasi->id)
            ->firstOrFail();

        $questions = SurveyQuestion::aktif()->with('options')->get();
        $total = $questions->count();

        if ($total === 0) {
            return back()->withErrors(['survey' => 'Tidak ada soal survey tersedia.']);
        }

        $benar = 0;
        foreach ($questions as $q) {
            $jawaban = $request->jawaban[$q->id] ?? null;
            $jawabanBenar = $q->options->where('is_benar', true)->first()?->id;
            if ($jawaban && (int) $jawaban === (int) $jawabanBenar) {
                $benar++;
            }
        }

        $skor = (int) round(($benar / $total) * 100);
        $lulus = $skor === 100;

        $pekerja->increment('survey_attempt');
        $pekerja->update([
            'survey_skor'     => $skor,
            'survey_lulus'    => $lulus,
            'survey_lulus_at' => $lulus ? now() : null,
        ]);

        session()->forget(['survey_order', 'survey_options_order']);

        if ($lulus) {
            return redirect()->route('vendor.survey', $token)
                ->with('survey_result', [
                    'lulus'        => true,
                    'skor'         => $skor,
                    'nama_pekerja' => $pekerja->nama_pekerja,
                ]);
        }

        return redirect()->route('vendor.survey', $token)
            ->with('survey_result', [
                'lulus'        => false,
                'skor'         => $skor,
                'benar'        => $benar,
                'total'        => $total,
                'nama_pekerja' => $pekerja->nama_pekerja,
            ]);
    }
}
