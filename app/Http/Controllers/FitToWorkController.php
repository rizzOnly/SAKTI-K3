<?php
namespace App\Http\Controllers;

use App\Models\FitToWork;
use App\Models\FitToWorkPekerja;
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
            'nama_perusahaan' => 'nullable|string|max:200',
            'nama_pekerjaan'  => 'required|string|max:500',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'no_wa'           => 'nullable|string|max:20',
            'pekerjas'        => 'required|array|min:1',
            'pekerjas.*.nama'          => 'required|string|max:200',
            'pekerjas.*.jenis_kelamin' => 'required|in:L,P',
        ]);

        if ($data['tipe'] === 'vendor' && empty($data['nama_perusahaan'])) {
            return back()->withInput()
                ->withErrors(['nama_perusahaan' => 'Nama perusahaan wajib diisi untuk vendor.']);
        }

        // Buat submission utama
        $ftw = FitToWork::create([
            'tipe'            => $data['tipe'],
            'nama_perusahaan' => $data['nama_perusahaan'] ?? null,
            'nama_pekerjaan'  => $data['nama_pekerjaan'],
            'tanggal_mulai'   => $data['tanggal_mulai'],
            'tanggal_selesai' => $data['tanggal_selesai'],
            'no_wa'           => $data['no_wa'] ?? null,
            'is_active'       => true,
        ]);

        // Buat entri pekerja
        $namaList = [];
        foreach ($data['pekerjas'] as $p) {
            FitToWorkPekerja::create([
                'fit_to_work_id' => $ftw->id,
                'nama'           => $p['nama'],
                'jenis_kelamin'  => $p['jenis_kelamin'],
                'status'         => 'menunggu',
            ]);
            $namaList[] = $p['nama'];
        }

        $namaStr      = implode(', ', $namaList);
        $jumlah       = count($namaList);
        $perusahaan   = $ftw->nama_perusahaan ?? 'Internal PLN';

        // Notif ke dokter
        $dokters = User::role('dokter')->whereNotNull('no_hp')->get();
        foreach ($dokters as $dokter) {
            WhatsAppService::send($dokter->no_hp,
                "🏥 *Permintaan Pemeriksaan Fit to Work*\n\n" .
                "Perusahaan: {$perusahaan}\n" .
                "Pekerjaan: {$ftw->nama_pekerjaan}\n" .
                "Durasi: {$ftw->tanggal_mulai->format('d/m/Y')} s/d {$ftw->tanggal_selesai->format('d/m/Y')}\n" .
                "Jumlah Pekerja: {$jumlah} orang\n" .
                "Nama: {$namaStr}\n\n" .
                "Silakan lakukan pemeriksaan dan isi hasil di panel klinik."
            );
        }

        // Notif ke K3
        $adminK3s = User::role('admin_k3')->whereNotNull('no_hp')->get();
        foreach ($adminK3s as $admin) {
            WhatsAppService::send($admin->no_hp,
                "⚠️ *Pekerjaan Risiko Tinggi – Fit to Work*\n\n" .
                "Perusahaan: {$perusahaan}\n" .
                "Pekerjaan: {$ftw->nama_pekerjaan}\n" .
                "Durasi: {$ftw->tanggal_mulai->format('d/m/Y')} s/d {$ftw->tanggal_selesai->format('d/m/Y')}\n" .
                "Jumlah Pekerja: {$jumlah} orang\n" .
                "Status: Menunggu pemeriksaan dokter."
            );
        }

        return redirect()->route('fit-to-work.sukses')
            ->with('ftw_perusahaan', $perusahaan)
            ->with('ftw_jumlah', $jumlah);
    }

    public function sukses()
    {
        if (!session('ftw_perusahaan')) return redirect('/');
        return view('fit-to-work.sukses');
    }
}
