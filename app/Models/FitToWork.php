<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitToWork extends Model
{
    protected $fillable = [
        'tipe', 'nama', 'nama_perusahaan',
        'jenis_kelamin', 'nama_pekerjaan',
        'tanggal_mulai', 'tanggal_selesai',
        'no_wa', 'status', 'catatan_dokter',
        'tanggal_periksa', 'dokter_nama', 'is_active',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_periksa' => 'date',
        'is_active'       => 'boolean',
    ];

    // Scope: hanya vendor, status fit, dan masih berlaku
    public function scopeVendorFitAktif($q)
    {
        return $q->where('tipe', 'vendor')
                 ->where('status', 'fit')
                 ->where('is_active', true)
                 ->where('tanggal_selesai', '>=', today());
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'fit'        => 'Fit to Work',
            'tidak_fit'  => 'Tidak Fit',
            default      => 'Menunggu Pemeriksaan',
        };
    }
}
