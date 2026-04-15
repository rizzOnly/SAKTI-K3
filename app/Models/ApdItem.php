<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ApdItem extends Model
{
    protected $fillable = [
        'kode_barang', 'nama_barang', 'satuan', 'merk',
        'kondisi', 'stok', 'min_stok', 'is_consumable',
        'exp_date', 'lokasi_gudang', 'image_path',
    ];

    protected $casts = [
        'exp_date' => 'date',
        'is_consumable' => 'boolean',
    ];

    // Scope: item dengan stok kritis (di bawah minimum)
    public function scopeStokKritis(Builder $query): Builder
    {
        return $query->whereColumn('stok', '<=', 'min_stok');
    }

    // Scope: item yang akan expired dalam N hari
    public function scopeAkanExpired(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('exp_date')
            ->where('exp_date', '<=', now()->addDays($days));
    }

    // Generate nomor kode otomatis
    public static function generateKode(): string
    {
        $last = static::latest()->first();
        $no = $last ? ((int) substr($last->kode_barang ?? '0', 3)) + 1 : 1;
        return 'APD' . str_pad($no, 4, '0', STR_PAD_LEFT);
    }

    // Relasi
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    public function pengambilanDetails()
    {
        return $this->hasMany(PengambilanDetail::class);
    }

    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }
}
