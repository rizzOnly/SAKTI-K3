<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsBanner extends Model
{
    protected $fillable = ['image_path', 'title', 'urutan', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}
