<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlinikAlat extends Model
{
    protected $fillable = [
        'nama_barang', 'satuan', 'stok',
        'tgl_kalibrasi_terakhir', 'tgl_kalibrasi_ulang',
    ];

    protected $casts = [
        'tgl_kalibrasi_terakhir' => 'date',
        'tgl_kalibrasi_ulang' => 'date',
    ];
}
