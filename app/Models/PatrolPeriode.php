<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatrolPeriode extends Model
{
    protected $fillable = ['judul', 'bulan', 'tahun', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function jadwals()
    {
        return $this->hasMany(PatrolJadwal::class)->orderBy('urutan'); // ← pakai urutan
    }

    public function jadwalsMingguIni()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek   = now()->endOfWeek();

        return $this->hasMany(PatrolJadwal::class)
            ->whereBetween('tanggal_patrol', [$startOfWeek, $endOfWeek])
            ->orderBy('urutan'); // ← pakai urutan
    }

    public static function namaBulan(int $bulan): string
    {
        return [
            1=>'Januari', 2=>'Februari', 3=>'Maret',
            4=>'April',   5=>'Mei',      6=>'Juni',
            7=>'Juli',    8=>'Agustus',  9=>'September',
            10=>'Oktober',11=>'November',12=>'Desember',
        ][$bulan] ?? '';
    }

    public function getNamaBulanAttribute(): string
    {
        return self::namaBulan($this->bulan);
    }
}
