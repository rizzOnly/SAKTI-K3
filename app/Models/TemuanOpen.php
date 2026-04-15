<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TemuanOpen extends Model
{
    protected $fillable = ['bidang', 'jumlah_temuan', 'urutan', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function scopeAktif($q)
    {
        return $q->where('is_active', true)->orderBy('urutan');
    }
}
