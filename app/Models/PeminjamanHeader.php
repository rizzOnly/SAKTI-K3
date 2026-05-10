<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanHeader extends Model
{
    protected $fillable = [
        'nomor_transaksi', 'user_id', 'is_guest',
        'guest_nama', 'guest_perusahaan', 'guest_no_wa', 'guest_email',
        'tanggal_pengajuan', 'tanggal_kembali_rencana', 'status', 'catatan',
        'berkas_jsa',          // ← tambahkan
        'foto_dokumentasi',
        'approved_by', 'approved_at', 'rejection_reason',
        'returned_at', 'kondisi_kembali',
        // Reminder tracking
        'reminder_h1_sent_at',
        'reminder_jatuh_tempo_sent_at',
        'reminder_terlambat_last_sent_at',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'approved_at' => 'datetime',
        'returned_at' => 'datetime',
        // Reminder tracking timestamps
        'reminder_h1_sent_at'              => 'datetime',
        'reminder_jatuh_tempo_sent_at'     => 'datetime',
        'reminder_terlambat_last_sent_at'  => 'datetime',
    ];

    public static function generateNomor(): string
    {
        $prefix = 'PJM-' . date('Ymd') . '-';
        $last = static::where('nomor_transaksi', 'like', $prefix . '%')->latest()->first();
        $no = $last ? ((int) substr($last->nomor_transaksi, -4)) + 1 : 1;
        return $prefix . str_pad($no, 4, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }
}
