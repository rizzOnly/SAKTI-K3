<?php

namespace App\Http\Controllers;

use App\Models\FitToWork;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class FitToWorkController extends Controller
{
    public function show()
    {
        return view('fit-to-work.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipe'            => 'required|in:internal,vendor',
            'nama'            => 'required|string|max:200',
            'nama_perusahaan' => 'nullable|string|max:200',
            'jenis_kelamin'   => 'required|in:L,P',
            'nama_pekerjaan'  => 'required|string|max:500',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'no_wa'           => 'nullable|string|max:20',
        ]);

        // Validation: nama_perusahaan required for vendor
        if ($data['tipe'] === 'vendor' && empty($data['nama_perusahaan'])) {
            return back()->withInput()
                ->withErrors(['nama_perusahaan' => 'Nama perusahaan wajib diisi untuk vendor.']);
        }

        $ftw = FitToWork::create($data);

        // Notify dokter klinik via WhatsApp
        $dokters = User::role('dokter')->whereNotNull('no_hp')->get();
        foreach ($dokters as $dokter) {
            WhatsAppService::send($dokter->no_hp,
                "🏥 *Permintaan Pemeriksaan Fit to Work*\n\n" .
                "Nama: {$ftw->nama}\n" .
                "Perusahaan: " . ($ftw->nama_perusahaan ?? 'Internal PLN') . "\n" .
                "Pekerjaan: {$ftw->nama_pekerjaan}\n" .
                "Durasi: {$ftw->tanggal_mulai->format('d/m/Y')} s/d {$ftw->tanggal_selesai->format('d/m/Y')}\n" .
                "Jenis Kelamin: {$ftw->jenis_kelamin_label}\n\n" .
                "Silakan lakukan pemeriksaan dan isi hasil di panel klinik."
            );
        }

        // Notify admin K3 via WhatsApp
        $adminK3s = User::role('admin_k3')->whereNotNull('no_hp')->get();
        foreach ($adminK3s as $admin) {
            WhatsAppService::send($admin->no_hp,
                "⚠️ *Pekerjaan Risiko Tinggi – Fit to Work*\n\n" .
                "Nama: {$ftw->nama}\n" .
                "Perusahaan: " . ($ftw->nama_perusahaan ?? 'Internal PLN') . "\n" .
                "Pekerjaan: {$ftw->nama_pekerjaan}\n" .
                "Durasi: {$ftw->tanggal_mulai->format('d/m/Y')} s/d {$ftw->tanggal_selesai->format('d/m/Y')}\n" .
                "Status: Menunggu pemeriksaan dokter."
            );
        }

        return redirect()->route('fit-to-work.sukses')
            ->with('ftw_nama', $ftw->nama);
    }

    public function sukses()
    {
        if (!session('ftw_nama')) {
            return redirect('/');
        }
        return view('fit-to-work.sukses');
    }
}
