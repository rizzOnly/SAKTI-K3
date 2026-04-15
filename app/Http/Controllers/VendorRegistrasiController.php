<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{VendorRegistrasi, VendorPekerja, SurveyQuestion};
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Session;

class VendorRegistrasiController extends Controller
{
    // ─── STEP 1: Form Registrasi ────────────────────────────

    public function showRegistrasi()
    {
        return view('vendor.form-registrasi');
    }

    public function storeRegistrasi(Request $request)
    {
        $data = $request->validate([
            'nama_perusahaan'     => 'required|string|max:200',
            'nama_pekerjaan'      => 'required|string|max:500',
            'tanggal_mulai'       => 'required|date',
            'tanggal_selesai'     => 'required|date|after_or_equal:tanggal_mulai',
            'no_wa_pic'           => 'nullable|string|max:20',
            'email_pic'           => 'nullable|email|max:255',
            // Pekerja: minimal 1
            'pekerjas'            => 'required|array|min:1',
            'pekerjas.*.nama'     => 'required|string|max:100',
        ]);

        // Wajib salah satu kontak
        if (empty($data['no_wa_pic']) && empty($data['email_pic'])) {
            return back()->withInput()
                ->withErrors(['kontak' => 'Wajib isi minimal salah satu kontak (WA atau email) PIC.']);
        }

        // Buat registrasi (Token akan otomatis ter-generate berkat Model)
        $registrasi = VendorRegistrasi::create([
            'nama_perusahaan'  => $data['nama_perusahaan'],
            'nama_pekerjaan'   => $data['nama_pekerjaan'],
            'tanggal_mulai'    => $data['tanggal_mulai'],
            'tanggal_selesai'  => $data['tanggal_selesai'],
            'no_wa_pic'        => $data['no_wa_pic'],
            'email_pic'        => $data['email_pic'],
            'status'           => 'aktif',
            'is_active'        => true,
        ]);

        // Buat entri pekerja (belum lulus survey)
        foreach ($data['pekerjas'] as $p) {
            VendorPekerja::create([
                'vendor_registrasi_id' => $registrasi->id,
                'nama_pekerja'         => $p['nama'],
                'survey_lulus'         => false,
            ]);
        }

        // Notif admin
        $admins = \App\Models\User::role('admin_k3')->whereNotNull('no_hp')->get();
        foreach ($admins as $admin) {
            WhatsAppService::send($admin->no_hp,
                "🏢 *Registrasi Vendor Baru*\n" .
                "Perusahaan: {$registrasi->nama_perusahaan}\n" .
                "Pekerjaan: {$registrasi->nama_pekerjaan}\n" .
                "Pekerja: " . count($data['pekerjas']) . " orang\n" .
                "Survey link: {$registrasi->survey_url}"
            );
        }

        // Redirect ke halaman survey dengan token
        return redirect()->route('vendor.survey', $registrasi->token_registrasi)
            ->with('registrasi_baru', true);
    }


    // ─── STEP 2: Halaman Survey ─────────────────────────────

    public function showSurvey(string $token)
    {
        $registrasi = VendorRegistrasi::where('token_registrasi', $token)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Cek masa berlaku
        if ($registrasi->tanggal_selesai < today()) {
            return view('vendor.survey-expired', compact('registrasi'));
        }

        $pekerjas  = $registrasi->pekerjas()->orderBy('id')->get();
        $questions = SurveyQuestion::aktif()->get();

        if ($questions->isEmpty()) {
            return view('vendor.survey-kosong');
        }

        return view('vendor.survey', compact('registrasi', 'pekerjas', 'questions'));
    }

    // Preview (untuk admin)
    public function previewSurvey()
    {
        $questions = SurveyQuestion::aktif()->get();
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
        $total     = $questions->count();

        if ($total === 0) {
            return back()->withErrors(['survey' => 'Tidak ada soal survey tersedia.']);
        }

        // Hitung skor
        $benar = 0;
        foreach ($questions as $q) {
            $jawaban      = $request->jawaban[$q->id] ?? null;
            $jawabanBenar = $q->options->where('is_benar', true)->first()?->id;
            if ($jawaban && (int)$jawaban === (int)$jawabanBenar) {
                $benar++;
            }
        }

        $skor  = (int) round(($benar / $total) * 100);
        $lulus = $skor === 100; // Standar K3: Lulus jika 100% benar

        // Update data pekerja
        $pekerja->increment('survey_attempt');
        $pekerja->update([
            'survey_skor'    => $skor,
            'survey_lulus'   => $lulus,
            'survey_lulus_at'=> $lulus ? now() : null,
        ]);

        if ($lulus) {
            return redirect()->route('vendor.survey', $token)
                ->with('survey_result', [
                    'lulus'        => true,
                    'skor'         => $skor,
                    'nama_pekerja' => $pekerja->nama_pekerja,
                ]);
        }

        // Tidak lulus → kembali ke halaman survey dengan pesan
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
