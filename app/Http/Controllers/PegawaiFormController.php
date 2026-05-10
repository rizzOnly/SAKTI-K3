<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, ApdItem, PengambilanHeader, PengambilanDetail, PeminjamanHeader, PeminjamanDetail, KlinikAppointment};
use App\Notifications\ApprovalNotification;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PegawaiFormController extends Controller
{
    // =========================
    // 1. APD
    // =========================

    public function showApd()
    {
        $apdConsumable = ApdItem::where('is_consumable', true)
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $apdReturnable = ApdItem::where('is_consumable', false)
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('pegawai.form-apd', compact('apdConsumable', 'apdReturnable'));
    }

    public function storeAmbil(Request $request)
    {
        $request->validate([
            'nid'                 => 'required|string',
            'tanggal_pengajuan'   => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.apd_item_id' => 'required|exists:apd_items,id',
            'items.*.jumlah'      => 'required|integer|min:1',
            'catatan'             => 'nullable|string|max:500',
            'no_wa_pengirim'      => 'nullable|string|max:20',
            'email_pengirim'      => 'nullable|email|max:255',
            'berkas_permit'       => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'berkas_permit.required' => 'Berkas Permit / JSA wajib diupload.',
            'berkas_permit.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'berkas_permit.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        if (empty($request->no_wa_pengirim) && empty($request->email_pengirim)) {
            return back()->withInput()->withErrors(['kontak' => 'Wajib isi minimal salah satu kontak.']);
        }

        $user = User::where('nid', $request->nid)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['nid' => 'NID tidak ditemukan. Hubungi Admin K3.']);
        }

        // ── Auto-update kontak user ──────────────────────────
        $updateData = [];
        if (!empty($request->no_wa_pengirim)) {
            $updateData['no_hp'] = $request->no_wa_pengirim;
        }
        if (!empty($request->email_pengirim)) {
            $isDummyEmail = empty($user->email)
                || str_contains($user->email, 'pln.com')
                || str_contains($user->email, 'example.com');
            if ($isDummyEmail) {
                $updateData['email'] = $request->email_pengirim;
            }
        }
        if (!empty($updateData)) {
            $user->update($updateData);
            $user->refresh();
        }
        // ────────────────────────────────────────────────────

        $berkasPath = null;
        if ($request->hasFile('berkas_permit')) {
            $berkasPath = $request->file('berkas_permit')->store('apd/permit', 'public');
        }

        $header = PengambilanHeader::create([
            'nomor_transaksi'   => PengambilanHeader::generateNomor(),
            'user_id'           => $user->id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status'            => 'pending',
            'catatan'           => $request->catatan,
            'berkas_permit'     => $berkasPath,
        ]);

        foreach ($request->items as $item) {
            PengambilanDetail::create([
                'pengambilan_header_id' => $header->id,
                'apd_item_id' => $item['apd_item_id'],
                'jumlah' => $item['jumlah'],
            ]);
        }

        $detailItems = "";
        foreach ($request->items as $item) {
            $apd = ApdItem::find($item['apd_item_id']);
            if ($apd) {
                $detailItems .= "  • {$apd->nama_barang} × {$item['jumlah']}\n";
            }
        }

        $admins = User::role('admin_k3')->get();
        foreach ($admins as $admin) {
            if ($admin->no_hp) {
                WhatsAppService::send($admin->no_hp,
                    "📦 *Pengajuan Pengambilan APD Baru*\n" .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    "👤 Nama: {$user->name} ({$user->nid})\n" .
                    "🏢 Bidang: " . ($user->bidang ?? '-') . "\n" .
                    "📋 No: {$header->nomor_transaksi}\n" .
                    "📅 Tanggal: " . Carbon::parse($request->tanggal_pengajuan)->format('d/m/Y') . "\n" .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    "🦺 *Detail Barang:*\n" .
                    $detailItems .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    ($request->catatan ? "📝 {$request->catatan}\n" : "") .
                    "📎 Berkas Permit: Terlampir di Sistem\n" .
                    "✅ Approve: " . url('/admin')
                );
            }
        }

        $pesan = "✅ *Pengajuan APD Diterima*\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "Nama: {$user->name}\n" .
            "Bidang: " . ($user->bidang ?? '-') . "\n" .
            "No: {$header->nomor_transaksi}\n" .
            "Tanggal: " . Carbon::parse($request->tanggal_pengajuan)->format('d/m/Y') . "\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "🦺 *Detail Barang:*\n" .
            $detailItems .
            "━━━━━━━━━━━━━━━━━━\n" .
            "Status: Pending";

        if ($request->no_wa_pengirim) WhatsAppService::send($request->no_wa_pengirim, $pesan);

        if ($request->email_pengirim) {
            Mail::raw(strip_tags($pesan), function ($msg) use ($request) {
                $msg->to($request->email_pengirim)->subject('Pengajuan APD');
            });
        }

        return back()->with('success', 'Berhasil kirim pengajuan, Silahkan ke Admin K3 untuk approval');
    }

    // =========================
    // 2. PINJAM
    // =========================

    public function storePinjam(Request $request)
    {
        $request->validate([
            'nid'                     => 'required|string',
            'tanggal_pengajuan'       => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:today',
            'items'                   => 'required|array|min:1',
            'items.*.apd_item_id'     => 'required|exists:apd_items,id',
            'items.*.jumlah'          => 'required|integer|min:1',
            'catatan'                 => 'nullable|string|max:500',
            'no_wa_pengirim'          => 'nullable|string|max:20',
            'email_pengirim'          => 'nullable|email|max:255',
            'berkas_jsa'              => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'berkas_jsa.required' => 'Berkas JSA wajib diupload.',
            'berkas_jsa.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'berkas_jsa.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        if (empty($request->no_wa_pengirim) && empty($request->email_pengirim)) {
            return back()->withInput()->withErrors(['kontak' => 'Wajib isi minimal salah satu kontak.']);
        }

        $user = User::where('nid', $request->nid)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['nid' => 'NID tidak ditemukan. Hubungi Admin K3.']);
        }

        // ── Auto-update kontak user ──────────────────────────
        $updateData = [];
        if (!empty($request->no_wa_pengirim)) {
            $updateData['no_hp'] = $request->no_wa_pengirim;
        }
        if (!empty($request->email_pengirim)) {
            $isDummyEmail = empty($user->email)
                || str_contains($user->email, 'pln.com')
                || str_contains($user->email, 'example.com');
            if ($isDummyEmail) {
                $updateData['email'] = $request->email_pengirim;
            }
        }
        if (!empty($updateData)) {
            $user->update($updateData);
            $user->refresh();
        }
        // ────────────────────────────────────────────────────

        $jsaPath = null;
        if ($request->hasFile('berkas_jsa')) {
            $jsaPath = $request->file('berkas_jsa')->store('apd/jsa', 'public');
        }

        $header = PeminjamanHeader::create([
            'nomor_transaksi'         => PeminjamanHeader::generateNomor(),
            'user_id'                 => $user->id,
            'tanggal_pengajuan'       => $request->tanggal_pengajuan,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status'                  => 'pending',
            'catatan'                 => $request->catatan,
            'berkas_jsa'              => $jsaPath,
        ]);

        foreach ($request->items as $item) {
            PeminjamanDetail::create([
                'peminjaman_header_id' => $header->id,
                'apd_item_id'          => $item['apd_item_id'],
                'jumlah'               => $item['jumlah'],
            ]);
        }

        $detailItemsPinjam = "";
        foreach ($request->items as $item) {
            $apd = ApdItem::find($item['apd_item_id']);
            if ($apd) {
                $detailItemsPinjam .= "  • {$apd->nama_barang} × {$item['jumlah']}\n";
            }
        }

        $admins = User::role('admin_k3')->get();

        foreach ($admins as $admin) {
            if ($admin->no_hp) {
                WhatsAppService::send($admin->no_hp,
                    "🔄 *Pengajuan Peminjaman APD Baru*\n" .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    "👤 Nama:  {$user->name}\n" .
                    "🏢 Bidang:  " . ($user->bidang ?? '-') . "\n" .
                    "📋 Nomor Transaksi: {$header->nomor_transaksi}\n" .
                    "📅 Tanggal Pengajuan: " . Carbon::parse($request->tanggal_pengajuan)->format('d/m/Y') . "\n" .
                    "🔙 Tanggal Rencana Kembali: " . Carbon::parse($request->tanggal_kembali_rencana)->format('d/m/Y') . "\n" .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    "🦺 *Detail Barang:*\n" .
                    $detailItemsPinjam .
                    "━━━━━━━━━━━━━━━━━━\n" .
                    ($request->catatan ? "📝 {$request->catatan}\n" : "") .
                    "📎 Berkas JSA: Terlampir di Sistem\n" .
                    "✅ Approve: " . url('/admin')
                );
            }
        }

        $pesan = "✅ *Pengajuan Peminjaman APD Diterima*\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "Nama: {$user->name}\n" .
            "Bidang: " . ($user->bidang ?? '-') . "\n" .
            "No: {$header->nomor_transaksi}\n" .
            "Tanggal: " . Carbon::parse($request->tanggal_pengajuan)->format('d/m/Y') . "\n" .
            "━━━━━━━━━━━━━━━━━━\n" .
            "🦺 *Detail Barang:*\n" .
            $detailItemsPinjam .
            "━━━━━━━━━━━━━━━━━━\n" .
            "Status: Pending (Menunggu Admin K3)";

        if ($request->no_wa_pengirim) WhatsAppService::send($request->no_wa_pengirim, $pesan);
        if ($request->email_pengirim) {
            Mail::raw(strip_tags(str_replace(['*', '━'], ['', '-'], $pesan)), function ($m) use ($request, $header) {
                $m->to($request->email_pengirim)->subject("Peminjaman APD – {$header->nomor_transaksi}");
            });
        }

        return back()->with('success', 'Berhasil kirim pengajuan pinjam, Silahkan ke Admin K3 untuk approval');
    }

    // =========================
    // 3. BOOKING
    // =========================

    public function showBooking()
    {
        $dokters = User::role('dokter')->get();
        return view('pegawai.form-booking', compact('dokters'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'nid'            => 'required|string',
            'dokter_id'      => 'required|exists:users,id',
            'tanggal'        => 'required|date|after:today',
            'jam_slot'       => 'required|string',
            'keluhan'        => 'nullable|string|max:500',
            'no_wa_pengirim' => 'nullable|string|max:20',
            'email_pengirim' => 'nullable|email|max:255',
            'bidang'         => 'nullable|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
        ]);

        if (empty($request->no_wa_pengirim) && empty($request->email_pengirim)) {
            return back()->withInput()->withErrors(['kontak' => 'Wajib mengisi minimal salah satu: Nomor WhatsApp atau Email.']);
        }

        $user = User::where('nid', $request->nid)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['nid' => 'NID tidak ditemukan. Hubungi Admin K3 untuk mendaftarkan NID Anda terlebih dahulu.']);
        }

        // ── Auto-update kontak + bidang + jenis kelamin ──────
        $updateData = [];

        if (!empty($request->no_wa_pengirim)) {
            $updateData['no_hp'] = $request->no_wa_pengirim;
        }

        if (!empty($request->email_pengirim)) {
            $isDummyEmail = empty($user->email)
                || str_contains($user->email, 'pln.com')
                || str_contains($user->email, 'example.com');
            if ($isDummyEmail) {
                $updateData['email'] = $request->email_pengirim;
            }
        }

        // Update bidang jika belum terisi
        if (!empty($request->bidang) && empty($user->bidang)) {
            $updateData['bidang'] = $request->bidang;
        }

        // Update jenis kelamin jika belum terisi
        if (!empty($request->jenis_kelamin) && empty($user->jenis_kelamin)) {
            $updateData['jenis_kelamin'] = $request->jenis_kelamin;
        }

        if (!empty($updateData)) {
            $user->update($updateData);
            $user->refresh();
        }
        // ────────────────────────────────────────────────────

        if (!KlinikAppointment::isSlotTersedia($request->dokter_id, $request->tanggal, $request->jam_slot)) {
            return back()->withErrors(['jam_slot' => 'Slot sudah terisi oleh pegawai lain. Pilih jam atau tanggal lain.'])->withInput();
        }

        $appointment = KlinikAppointment::create([
            'user_id'   => $user->id,
            'dokter_id' => $request->dokter_id,
            'tanggal'   => $request->tanggal,
            'jam_slot'  => $request->jam_slot,
            'keluhan'   => $request->keluhan,
            'status'    => 'scheduled',
        ]);

        $dokter = User::find($request->dokter_id);
        $tgl    = $appointment->tanggal->format('d/m/Y');

        if ($dokter->no_hp) {
            WhatsAppService::send($dokter->no_hp,
                "🏥 *Appointment Klinik Baru*\n" .
                "Pasien: {$user->name} ({$user->nid})\n" .
                "Tanggal: {$tgl} – {$request->jam_slot}\n" .
                "Keluhan: " . ($request->keluhan ?? '-')
            );
        }
        if ($dokter->email) {
            $dokter->notify(new ApprovalNotification(
                'Appointment Klinik Baru',
                "Pasien {$user->name} membooking jadwal pada {$tgl} jam {$request->jam_slot}.",
                url('/klinik/appointments/' . $appointment->id)
            ));
        }

        $pesanKonfirmasi = "✅ *Booking Klinik Dikonfirmasi*\n━━━━━━━━━━━━━━━━━━\nNama: {$user->name}\nDokter: {$dokter->name}\nTanggal: {$tgl} – {$request->jam_slot}\n━━━━━━━━━━━━━━━━━━\nTolong hadir tepat waktu 😊.";
        if ($request->no_wa_pengirim) WhatsAppService::send($request->no_wa_pengirim, $pesanKonfirmasi);
        if ($request->email_pengirim) {
            Mail::raw(strip_tags(str_replace(['*', '━'], ['', '-'], $pesanKonfirmasi)), function ($message) use ($request, $tgl, $dokter) {
                $message->to($request->email_pengirim)->subject("Booking Klinik Dikonfirmasi – {$tgl} jam {$request->jam_slot}");
            });
        }

        return redirect()->route('pegawai.booking')
            ->with('success', "Booking berhasil! Jadwal Anda: {$tgl} jam {$request->jam_slot} bersama {$dokter->name}.");
    }

    // ══════════════════════════════════════════════════════════
    // 3. API ENDPOINTS (UNTUK AJAX)
    // ══════════════════════════════════════════════════════════

    public function getSlots(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:users,id',
            'tanggal'   => 'required|date',
        ]);

        $allSlots  = KlinikAppointment::getAllSlots();
        $available = KlinikAppointment::getSlotTersedia($request->dokter_id, $request->tanggal);
        $booked    = array_values(array_diff($allSlots, $available));

        return response()->json([
            'available' => array_values($available),
            'booked'    => $booked,
        ]);
    }

    public function cekNip(Request $request)
    {
        $user = User::where('nid', $request->nid)->first();

        if (!$user) {
            return response()->json([
                'found'   => false,
                'message' => 'NID tidak ditemukan.',
            ]);
        }

        return response()->json([
            'found'         => true,
            'nama'          => $user->name,
            'bidang'        => $user->bidang,
            'jenis_kelamin' => $user->jenis_kelamin,
        ]);
    }
}
