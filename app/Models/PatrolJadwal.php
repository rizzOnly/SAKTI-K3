<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolJadwal extends Model
{
    protected $fillable = [
        'patrol_periode_id', 'nama_petugas',
        'tanggal_patrol', 'lokasi_unit',
        'sudah_lapor', 'lapor_at',
        'urutan', // ← tambah ini
    ];

    protected $casts = [
        'tanggal_patrol' => 'date',
        'sudah_lapor'    => 'boolean',
        'lapor_at'       => 'datetime',
    ];

    public function periode()
    {
        return $this->belongsTo(PatrolPeriode::class, 'patrol_periode_id');
    }

    public function getNamaHariAttribute(): string
    {
        $hari = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        return $hari[$this->tanggal_patrol->format('l')] ?? '';
    }
}
