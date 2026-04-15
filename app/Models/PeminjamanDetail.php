<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    protected $fillable = [
        'peminjaman_header_id', 'apd_item_id', 'jumlah',
    ];

    public function peminjamanHeader()
    {
        return $this->belongsTo(PeminjamanHeader::class);
    }

    public function apdItem()
    {
        return $this->belongsTo(ApdItem::class);
    }
}
