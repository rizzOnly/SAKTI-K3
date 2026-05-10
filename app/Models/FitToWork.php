<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitToWork extends Model
{
    protected $fillable = [
        'tipe', 'nama_perusahaan',
        'nama_pekerjaan',
        'tanggal_mulai', 'tanggal_selesai',
        'no_wa', 'is_active',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_active'       => 'boolean',
    ];

    public function pekerjas()
    {
        return $this->hasMany(FitToWorkPekerja::class);
    }

    public function pekerjasfit()
    {
        return $this->hasMany(FitToWorkPekerja::class)->where('status', 'fit');
    }

    // Scope: submission vendor yang masih berlaku
    // Landing page: tampilkan pekerja fit dari submission yang belum expired
    public function scopeAktifDanBerlaku($q)
    {
        return $q->where('is_active', true)
                 ->where('tanggal_selesai', '>=', today());
    }

    // Scope lama untuk kompatibilitas (diganti)
    public function scopeVendorFitAktif($q)
    {
        return $q->where('tipe', 'vendor')
                 ->where('is_active', true)
                 ->where('tanggal_selesai', '>=', today())
                 ->whereHas('pekerjasfit');
    }
}
