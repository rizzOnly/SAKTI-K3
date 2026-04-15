<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'pertanyaan', 'gambar_soal', 'urutan', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function options()
    {
        return $this->hasMany(SurveyOption::class)->orderBy('urutan');
    }

    public function correctOption()
    {
        return $this->hasOne(SurveyOption::class)->where('is_benar', true);
    }

    // Scope: hanya soal aktif, urut
    public function scopeAktif($q)
    {
        return $q->where('is_active', true)->orderBy('urutan');
    }
}
