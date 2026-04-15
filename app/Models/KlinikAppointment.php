<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KlinikAppointment extends Model
{
    protected $fillable = [
        'user_id', 'dokter_id', 'tanggal', 'jam_slot',
        'keluhan', 'status', 'reminder_sent_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'reminder_sent_at' => 'datetime',
    ];

    // Semua slot yang tersedia dalam sehari (07:00 - 16:00, interval 30 menit)
    public static function getAllSlots(): array
    {
        $slots = [];
        $start = strtotime('07:00');
        $end = strtotime('16:00');
        for ($time = $start; $time <= $end; $time += 30 * 60) {
            $slots[] = date('H:i', $time);
        }
        return $slots;
    }

    // Ambil slot yang masih tersedia untuk dokter & tanggal tertentu
    public static function getSlotTersedia(int $dokterId, string $tanggal): array
    {
        $booked = static::where('dokter_id', $dokterId)
            ->where('tanggal', $tanggal)
            ->where('status', '!=', 'cancelled')
            ->pluck('jam_slot')
            ->toArray();

        return array_diff(static::getAllSlots(), $booked);
    }

    // Cek apakah slot masih tersedia
    public static function isSlotTersedia(int $dokterId, string $tanggal, string $slot): bool
    {
        return !static::where('dokter_id', $dokterId)
            ->where('tanggal', $tanggal)
            ->where('jam_slot', $slot)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function rekamMedis()
    {
        return $this->hasOne(KlinikRekamMedis::class, 'appointment_id');
    }
}
