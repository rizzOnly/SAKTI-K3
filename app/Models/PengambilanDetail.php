<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengambilanDetail extends Model
{
    protected $fillable = [
        'pengambilan_header_id', 'apd_item_id', 'jumlah',
    ];

    public function pengambilanHeader()
    {
        return $this->belongsTo(PengambilanHeader::class);
    }

    public function apdItem()
    {
        return $this->belongsTo(ApdItem::class);
    }
}
