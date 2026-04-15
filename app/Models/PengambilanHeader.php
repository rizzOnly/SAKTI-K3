<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengambilanHeader extends Model
{
    protected $fillable = [
        'nomor_transaksi', 'user_id', 'tanggal_pengajuan',
        'status', 'catatan', 'approved_by', 'approved_at', 'rejection_reason',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'approved_at' => 'datetime',
    ];

    // Generate nomor transaksi otomatis
    public static function generateNomor(): string
    {
        $prefix = 'AMB-' . date('Ymd') . '-';
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
        return $this->hasMany(PengambilanDetail::class);
    }
}
