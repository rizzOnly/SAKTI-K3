<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlinikResepObat extends Model
{
    protected $fillable = [
        'rekam_medis_id', 'obat_id', 'jumlah', 'aturan_pakai',
    ];

    protected static function booted(): void
    {
        // Kurangi stok saat resep baru dibuat
        static::created(function (KlinikResepObat $resep) {
            $obat = KlinikObat::find($resep->obat_id);
            if ($obat && $obat->stok >= $resep->jumlah) {
                $obat->decrement('stok', $resep->jumlah);
            }
        });

        // Kembalikan stok lama & kurangi stok baru saat resep diupdate
        static::updated(function (KlinikResepObat $resep) {
            if ($resep->wasChanged('jumlah') || $resep->wasChanged('obat_id')) {
                $obatLama = KlinikObat::find($resep->getOriginal('obat_id'));
                if ($obatLama) {
                    $obatLama->increment('stok', $resep->getOriginal('jumlah'));
                }
                $obatBaru = KlinikObat::find($resep->obat_id);
                if ($obatBaru && $obatBaru->stok >= $resep->jumlah) {
                    $obatBaru->decrement('stok', $resep->jumlah);
                }
            }
        });

        // Kembalikan stok saat resep dihapus
        static::deleted(function (KlinikResepObat $resep) {
            $obat = KlinikObat::find($resep->obat_id);
            if ($obat) {
                $obat->increment('stok', $resep->jumlah);
            }
        });
    }

    public function rekamMedis()
    {
        return $this->belongsTo(KlinikRekamMedis::class);
    }

    public function obat()
    {
        return $this->belongsTo(KlinikObat::class, 'obat_id');
    }
}
