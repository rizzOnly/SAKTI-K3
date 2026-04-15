<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, ApdItem, PengambilanHeader, PengambilanDetail, PeminjamanHeader, PeminjamanDetail, KlinikAppointment};
use App\Notifications\ApprovalNotification;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Mail;

class PegawaiFormController extends Controller
{
    // ══════════════════════════════════════════════════════════
    // 1. PENGAMBILAN & PEMINJAMAN APD (GABUNGAN)
    // ══════════════════════════════════════════════════════════

    public function showApd()
    {
        // Ambil APD Consumable (Habis Pakai)
        $apdConsumable = ApdItem::where('is_consumable', true)
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        // Ambil APD Returnable (Dipinjam/Dikembalikan)
        $apdReturnable = ApdItem::where('is_consumable', false)
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('pegawai.form-apd', compact('apdConsumable', 'apdReturnable'));
    }

    public function storeAmbil(Request $request)
    {
        $request->validate([
            'nip'                 => 'required|string',
            'tanggal_pengajuan'   => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.apd_item_id' => 'required|exists:apd_items,id',
            'items.*.jumlah'      => 'required|integer|min:1',
            'catatan'             => 'nullable|string|max:500',
            'no_wa_pengirim'      => 'nullable|string|max:20',
            'email_pengirim'      => 'nullable|email|max:255',
        ], [
            'items.required' => 'Pilih minimal 1 item APD yang ingin diambil.',
        ]);

        if (empty($request->no_wa_pengirim) && empty($request->email_pengirim)) {
            return back()->withInput()->withErrors(['kontak' => 'Wajib mengisi minimal salah satu: Nomor WhatsApp atau Email.']);
        }

        $user = User::where('nip', $request->nip)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['nip' => 'NIP tidak ditemukan dalam sistem. Hubungi Admin K3.']);
        }

        $header = PengambilanHeader::create([
            'nomor_transaksi'   => PengambilanHeader::generateNomor(),
            'user_id'           => $user->id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status'            => 'pending',
            'catatan'           => $request->catatan,
        ]);

        foreach ($request->items as $item) {
            PengambilanDetail::create([
                'pengambilan_header_id' => $header->id,
                'apd_item_id'           => $item['apd_item_id'],
                'jumlah'                => $item['jumlah'],
            ]);
        }

        // Notifikasi ke Admin K3
        $admins = User::role('admin_k3')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                $admin->notify(new ApprovalNotification(
                    'Pengajuan Pengambilan APD Baru',
                    "Pegawai {$user->name} ({$user->nip}) mengajukan pengambilan APD.\nNo: {$header->nomor_transaksi}",
                    url('/admin/pengambilan-headers/' . $header->id)
                ));
            }
            if ($admin->no_hp) {
                WhatsAppService::send($admin->no_hp,
                    "📦 *Pengajuan APD Baru*\n" .
                    "👤 {$user->name} ({$user->nip})\n" .
                    "📋 No: {$header->nomor_transaksi}\n" .
                    "Approve di: " . url('/admin')
                );
            }
        }

        // Konfirmasi ke Pegawai
        $pesanKonfirmasi = "✅ *Pengajuan APD Diterima*\n━━━━━━━━━━━━━━━━━━\nNama: {$user->name}\nNo. Transaksi: {$header->nomor_transaksi}\nStatus: Menunggu persetujuan Admin K3\n━━━━━━━━━━━━━━━━━━\nPantau status melalui Admin K3 unit.";
        if ($request->no_wa_pengirim) WhatsAppService::send($request->no_wa_pengirim, $pesanKonfirmasi);
        if ($request->email_pengirim) {
            Mail::raw(strip_tags(str_replace(['*', '━'], ['', '-'], $pesanKonfirmasi)), function ($message) use ($request, $header) {
                $message->to($request->email_pengirim)->subject("Pengajuan APD Diterima – {$header->nomor_transaksi}");
            });
        }

        return redirect()->route('pegawai.apd')->with('success', "Pengajuan ambil APD berhasil dikirim! No: {$header->nomor_transaksi}.");
    }

    public function storePinjam(Request $request)
    {
        $request->validate([
            'nip'                     => 'required|string',
            'tanggal_pengajuan'       => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after:today',
            'items'                   => 'required|array|min:1',
            'items.*.apd_item_id'     => 'required|exists:apd_items,id',
            'items.*.jumlah'          => 'required|integer|min:1',
            'catatan'                 => 'nullable|string|max:500',
            'no_wa_pengirim'          => 'nullable|string|max:20',
            'email_pengirim'          => 'nullable|email|max:255',
        ], [
            'items.required' => 'Pilih minimal 1 item APD yang ingin dipinjam.',
        ]);

        if (empty($request->no_wa_pengirim) && empty($request->email_pengirim)) {
            return back()->withInput()->withErrors(['kontak' => 'Wajib mengisi minimal salah satu: Nomor WhatsApp atau Email.']);
        }

        $user = User::where('nip', $request->nip)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['nip' => 'NIP tidak ditemukan dalam sistem. Hubungi Admin K3.']);
        }

        $header = PeminjamanHeader::create([
            'nomor_transaksi'         => PeminjamanHeader::generateNomor(),
            'user_id'                 => $user->id,
            'tanggal_pengajuan'       => $request->tanggal_pengajuan,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'status'                  => 'pending',
            'catatan'                 => $request->catatan,
        ]);

        foreach ($request->items as $item) {
            PeminjamanDetail::create([
                'peminjaman_header_id' => $header->id,
                'apd_item_id'          => $item['apd_item_id'],
                'jumlah'               => $item['jumlah'],
            ]);
        }

        // Notifikasi ke Admin K3
        $admins = User::role('admin_k3')->get();
        foreach ($admins as $admin) {
            if ($admin->email) {
                $admin->notify(new ApprovalNotification(
                    'Pengajuan Peminjaman APD Baru',
                    "Pegawai {$user->name} ({$user->nip}) mengajukan peminjaman APD.\nNo: {$header->nomor_transaksi}",
                    url('/admin/peminjaman-headers/' . $header->id)
                ));
            }
            if ($admin->no_hp) {
                WhatsAppService::send($admin->no_hp,
                    "🔄 *Peminjaman APD Baru*\n" .
                    "👤 {$user->name} ({$user->nip})\n" .
                    "📋 No: {$header->nomor_transaksi}\n" .
                    "Approve di: " . url('/admin')
                );
            }
        }

        // Konfirmasi ke Pegawai
        $pesanKonfirmasi = "✅ *Peminjaman APD Diterima*\n━━━━━━━━━━━━━━━━━━\nNama: {$user->name}\nNo. Transaksi: {$header->nomor_transaksi}\nStatus: Menunggu persetujuan Admin K3\n━━━━━━━━━━━━━━━━━━\nHarap simpan nomor transaksi ini.";
        if ($request->no_wa_pengirim) WhatsAppService::send($request->no_wa_pengirim, $pesanKonfirmasi);
        if ($request->email_pengirim) {
            Mail::raw(strip_tags(str_replace(['*', '━'], ['', '-'], $pesanKonfirmasi)), function ($message) use ($request, $header) {
                $message->to($request->email_pengirim)->subject("Peminjaman APD Diterima – {$header->nomor_transaksi}");
            });
        }

        return redirect()->route('pegawai.apd')->with('success', "Pengajuan pinjam APD berhasil dikirim! No: {$header->nomor_transaksi}.");
    }


    // ══════════════════════════════════════════════════════════
    // 2. BOOKING KLINIK
    // ══════════════════════════════════════════════════════════

    public function showBooking()
    {
        $dokters = User::role('dokter')->get();
        return view('pegawai.form-booking', compact('dokters'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'nip'            => 'required|string',
            'dokter_id'      => 'required|exists:users,id',
            'tanggal'        => 'required|date|after:today',
            'jam_slot'       => 'required|string',
            'keluhan'        => 'nullable|string|max:500',
            'no_wa_pengirim' => 'nullable|string|max:20',
            'email_pengirim' => 'nullable|email|max:255',
            // --- TAMBAHAN BARU ---
            'bidang'         => 'nullable|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
        ]);

        if (empty($request->no_wa_pengirim) && empty($request->email_pengirim)) {
            return back()->withInput()->withErrors(['kontak' => 'Wajib mengisi minimal salah satu: Nomor WhatsApp atau Email.']);
        }

        $user = User::where('nip', $request->nip)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['nip' => 'NIP tidak ditemukan. Hubungi Admin K3 untuk mendaftarkan NIP Anda terlebih dahulu.']);
        }

        // --- TAMBAHAN BARU: Update Data Pegawai jika kosong ---
        if (!empty($request->bidang) && empty($user->bidang)) {
            $user->update(['bidang' => $request->bidang]);
        }
        if (!empty($request->jenis_kelamin) && empty($user->jenis_kelamin)) {
            $user->update(['jenis_kelamin' => $request->jenis_kelamin]);
        }

        // Anti-collision (Mencegah slot ganda)
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

        // Notifikasi ke Dokter
        if ($dokter->no_hp) {
            WhatsAppService::send($dokter->no_hp,
                "🏥 *Appointment Klinik Baru*\n" .
                "Pasien: {$user->name} ({$user->nip})\n" .
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

        // Konfirmasi ke Pegawai
        $pesanKonfirmasi = "✅ *Booking Klinik Dikonfirmasi*\n━━━━━━━━━━━━━━━━━━\nNama: {$user->name}\nDokter: {$dokter->name}\nTanggal: {$tgl} – {$request->jam_slot}\n━━━━━━━━━━━━━━━━━━\nHadir tepat waktu & bawa kartu pegawai.";
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
        $user = User::where('nip', $request->nip)->first();

        if (!$user) {
            return response()->json([
                'found'   => false,
                'message' => 'NIP tidak ditemukan.',
            ]);
        }

        return response()->json([
            'found'         => true,
            'nama'          => $user->name,
            'bidang'        => $user->bidang,
            'jenis_kelamin' => $user->jenis_kelamin, // TAMBAHAN
        ]);
    }
}
