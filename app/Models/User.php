<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use  HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nip', 'name', 'email', 'password', 'bidang', 'no_hp', 'jenis_kelamin',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke pengambilan APD
    public function pengambilan()
    {
        return $this->hasMany(PengambilanHeader::class);
    }

    // Relasi ke peminjaman APD
    public function peminjaman()
    {
        return $this->hasMany(PeminjamanHeader::class);
    }

    // Relasi ke appointment sebagai pasien
    public function appointments()
    {
        return $this->hasMany(KlinikAppointment::class);
    }

    // Relasi ke rekam medis sebagai pasien
    public function rekamMedis()
    {
        return $this->hasMany(KlinikRekamMedis::class);
    }
}
