<?php

namespace App\Helpers;

use App\Models\ApdItem;

class ApdInspectionHelper
{
    /**
     * Get inspection criteria for a given APD item based on its name
     *
     * @param string $apdName
     * @return array
     */
    public static function getCriteriaForItem(string $apdName): array
    {
        $criteriaMap = config('apd_inspection_criteria.criteria_mapping', []);
        $apdName = strtolower($apdName);

        foreach ($criteriaMap as $keyword => $mapping) {
            if (str_contains($apdName, $keyword)) {
                return $mapping['criteria'];
            }
        }

        // Default generic criteria if no match found
        return [
            ['nama' => 'Fungsi Utuh/Normal', 'key' => 'fungsi_baik'],
            ['nama' => 'Tidak Ada Kerusakan Fisik', 'key' => 'tanpa_rusak'],
            ['nama' => 'Label/Marking Terlihat', 'key' => 'label_terlihat'],
            ['nama' => 'Kebersihan Terjaga', 'key' => 'kebersihan'],
            ['nama' => 'Tidak Ada Bau Tidak Sedap', 'key' => 'tanpa_bau'],
        ];
    }

    /**
     * Process inspection finalization - update stock and item conditions
     *
     * @param \App\Models\ApdInspection $inspection
     * @return void
     */
    public static function finalizeInspection($inspection): void
    {
        foreach ($inspection->details as $detail) {
            $item = ApdItem::find($detail->apd_item_id);
            if (!$item) {
                continue;
            }

            // Update item condition based on inspection result
            $item->kondisi = $detail->kondisi_sesudah;
            $item->save();

            // If item is marked as unfit, reduce stock by 1
            if ($detail->is_tidak_layak) {
                $item->decrement('stok', 1);

                // Record as stock adjustment for audit trail
                \App\Models\StockAdjustment::create([
                    'apd_item_id' => $item->id,
                    'apd_inspection_id' => $inspection->id,
                    'user_id' => $inspection->user_id,
                    'tipe' => 'kurang',
                    'jumlah' => 1,
                    'keterangan' => 'Pengurangan karena inspeksi bulanan - Item tidak layak. Alasan: ' . ($detail->alasan_tidak_layak ?? 'Tidak disebutkan'),
                ]);
            }
        }
    }

    /**
     * Get inspection statistics for a given month
     *
     * @param string $month (Y-m format)
     * @return array
     */
    public static function getMonthlyStats(string $month): array
    {
        $inspection = \App\Models\ApdInspection::where('bulan', $month)->first();
        if (!$inspection) {
            return [
                'total_items' => 0,
                'fit_items' => 0,
                'unfit_items' => 0,
                'total_unfit_criteria' => 0,
            ];
        }

        $details = $inspection->details;
        $totalItems = $details->count();
        $unfitItems = $details->where('is_tidak_layak', true)->count();
        $fitItems = $totalItems - $unfitItems;

        $totalUnfitCriteria = 0;
        foreach ($details as $detail) {
            $totalUnfitCriteria += $detail->unfit_criteria_count;
        }

        return [
            'total_items' => $totalItems,
            'fit_items' => $fitItems,
            'unfit_items' => $unfitItems,
            'total_unfit_criteria' => $totalUnfitCriteria,
        ];
    }
}
