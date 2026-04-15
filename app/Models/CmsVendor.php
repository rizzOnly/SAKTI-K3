<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsVendor extends Model
{
    protected $fillable = [
        'nama_vendor', 'nama_pekerjaan',
        'tanggal_mulai', 'tanggal_selesai',
        'bidang_kerja', 'kontak', 'email',
        'is_active', 'kategori',
        'pekerja_json', // <--- Tambahkan ini
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'pekerja_json'    => 'array', // <--- Tambahkan ini (Wajib untuk Filament Repeater)
    ];

    public function flows()
    {
        return $this->hasMany(CmsVendorFlow::class);
    }
}
