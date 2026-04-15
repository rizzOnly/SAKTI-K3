<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'apd_item_id', 'user_id', 'tipe', 'jumlah', 'keterangan',
    ];

    public function apdItem()
    {
        return $this->belongsTo(ApdItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
