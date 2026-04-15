<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VendorRegistrasi extends Model
{
    protected $fillable = [
        'nama_perusahaan', 'nama_pekerjaan',
        'tanggal_mulai', 'tanggal_selesai',
        'no_wa_pic', 'email_pic',
        'status', 'is_active', 'token_registrasi',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_active'       => 'boolean',
    ];

    // Generate token unik saat dibuat
    protected static function booted(): void
    {
        static::creating(function (VendorRegistrasi $v) {
            $v->token_registrasi = Str::random(32);
        });
    }

    public function pekerjas()
    {
        return $this->hasMany(VendorPekerja::class);
    }

    // Scope: registrasi yang masih aktif & belum expired
    public function scopeAktifDanBerlaku($q)
    {
        return $q->where('is_active', true)
                 ->where('status', 'aktif')
                 ->where('tanggal_selesai', '>=', today());
    }

    // Pekerja yang lulus survey
    public function pekerjasLulus()
    {
        return $this->hasMany(VendorPekerja::class)->where('survey_lulus', true);
    }

    // Cek & update status expired otomatis
    public function updateStatusExpired(): void
    {
        if ($this->tanggal_selesai < today() && $this->status === 'aktif') {
            $this->update(['status' => 'expired']);
        }
    }

    // URL survey untuk PIC
    public function getSurveyUrlAttribute(): string
    {
        return url('/vendor/survey/' . $this->token_registrasi);
    }
}
