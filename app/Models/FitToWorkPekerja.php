<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FitToWorkPekerja extends Model
{
    protected $fillable = [
        'fit_to_work_id', 'nama', 'jenis_kelamin',
        'status', 'catatan_dokter',
        'tanggal_periksa', 'dokter_nama',
    ];

    protected $casts = [
        'tanggal_periksa' => 'date',
    ];

    public function submission()
    {
        return $this->belongsTo(FitToWork::class, 'fit_to_work_id');
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'fit'       => 'Fit to Work ✅',
            'tidak_fit' => 'Tidak Fit ❌',
            default     => 'Menunggu Pemeriksaan',
        };
    }
}
