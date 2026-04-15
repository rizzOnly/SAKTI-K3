<?php
namespace App\Observers;

use App\Models\StockAdjustment;
use App\Models\ApdItem;

class StockAdjustmentObserver
{
    public function created(StockAdjustment $adj): void
    {
        $item = ApdItem::find($adj->apd_item_id);
        if (!$item) return;

        if ($adj->tipe === 'tambah') {
            $item->increment('stok', $adj->jumlah);
        } else {
            $item->decrement('stok', $adj->jumlah);
        }
    }
}
