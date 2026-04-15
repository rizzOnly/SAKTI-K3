<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    protected $fillable = [
        'survey_question_id', 'teks_opsi', 'gambar_opsi', 'is_benar', 'urutan',
    ];

    protected $casts = ['is_benar' => 'boolean'];

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
}
