<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlinikRekamMedis extends Model
{
    protected $fillable = [
        'appointment_id', 'user_id', 'dokter_id',
        'diagnosa', 'tindakan', 'catatan',
    ];

    protected static function booted(): void
    {
        static::created(function (KlinikRekamMedis $rekamMedis) {
            // Stok obat ditangani di KlinikResepObat::booted()
            // Hanya update status appointment di sini
            if ($rekamMedis->appointment_id) {
                KlinikAppointment::where('id', $rekamMedis->appointment_id)
                    ->update(['status' => 'completed']);
            }
        });
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function appointment()
    {
        return $this->belongsTo(KlinikAppointment::class);
    }

    public function resepObat()
    {
        return $this->hasMany(KlinikResepObat::class, 'rekam_medis_id');
    }
}
