<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlinikObat extends Model
{
    protected $fillable = [
        'kode_obat', 'nama_barang', 'satuan',
        'stok', 'min_stok', 'tanggal_masuk', 'tanggal_exp',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_exp' => 'date',
    ];

    public function resepObats()
    {
        return $this->hasMany(KlinikResepObat::class, 'obat_id');
    }
}
