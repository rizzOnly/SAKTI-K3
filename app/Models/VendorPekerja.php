<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorPekerja extends Model
{
    protected $fillable = [
        'vendor_registrasi_id',
        'nama_pekerja', 'foto_pekerja',
        'survey_lulus', 'survey_skor',
        'survey_lulus_at', 'survey_attempt',
    ];

    protected $casts = [
        'survey_lulus'     => 'boolean',
        'survey_lulus_at'  => 'datetime',
    ];

    public function registrasi()
    {
        return $this->belongsTo(VendorRegistrasi::class, 'vendor_registrasi_id');
    }


}
