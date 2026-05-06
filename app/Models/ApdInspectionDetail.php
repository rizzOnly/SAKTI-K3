<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApdInspectionDetail extends Model
{
    protected $fillable = [
        'apd_inspection_id',
        'apd_item_id',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'is_tidak_layak',
        'alasan_tidak_layak',
        'kriteria_ceklist',
    ];

    protected $casts = [
        'kriteria_ceklist' => 'array', // JSON array of criteria checks
        'is_tidak_layak' => 'boolean',
    ];

    // Relationships
    public function apdInspection()
    {
        return $this->belongsTo(ApdInspection::class);
    }

    public function apdItem()
    {
        return $this->belongsTo(ApdItem::class);
    }

    /**
     * Check if item condition changed and became worse
     */
    public function conditionDegraded(): bool
    {
        $severity = ['baik' => 3, 'rusak' => 2, 'expired' => 1];
        $before = $severity[$this->kondisi_sebelum] ?? 3;
        $after = $severity[$this->kondisi_sesudah] ?? 3;

        return $after < $before;
    }

    /**
     * Get unfit criteria count
     */
    public function getUnfitCriteriaCountAttribute(): int
    {
        $criteria = $this->kriteria_ceklist ?? [];
        $unfitCount = 0;

        foreach ($criteria as $item) {
            if (isset($item['is_unfit']) && $item['is_unfit'] == true) {
                $unfitCount++;
            }
        }

        return $unfitCount;
    }
}
